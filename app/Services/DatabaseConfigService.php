<?php

namespace App\Services;

use PDO;
use PDOException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DatabaseConfigService
{
    /**
     * @var array
     */
    protected $config;

    /**
     * DatabaseConfigService constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Testa la connessione al database tramite PDO.
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $dsn = "{$this->config['dbType']}:host={$this->config['host']};port={$this->config['port']}";
            new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            return true;
        } catch (PDOException $e) {
            Log::error('Database connection failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Costruisce l'array di configurazione per la connessione.
     *
     * @return array
     */
    protected function buildConnectionConfig(): array
    {
        return [
            'driver'   => $this->config['dbType'],
            'host'     => $this->config['host'],
            'database' => $this->config['dbName'],
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'port'     => (int) $this->config['port'],
            // Altre impostazioni se necessarie...
        ];
    }

    /**
     * Imposta la nuova configurazione del database e riconnette.
     */
    public function connectConfig(): void
    {
        $dbType = $this->config['dbType'];
        config()->set('database.default', $dbType); // inserisce come connessione di default il nuovo driver
        config()->set("database.connections.{$dbType}", $this->buildConnectionConfig()); // inserisce come configurazione la configurazione attuale del servizio

        // Purga e riconnetti
        DB::purge($dbType);
        DB::reconnect($dbType);
    }

    /**
     * Restituisci la configurazione interna.
     *
     * @return array $config
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Aggiorna la configurazione interna.
     *
     * @param array $newConfig
     */
    public function updateData(array $newConfig): void
    {
        $this->config = $newConfig;
        Log::info('Configurazione aggiornata', $this->config);
    }

    /**
     * Aggiorna la configurazione e riconnette.
     *
     * @param array $newConfig
     */
    public function connectNewConfig(array $newConfig): void
    {
        $this->updateData($newConfig);
        $this->connectConfig();
    }

    /**
     * Esegue le migrazioni iniziali se necessario.
     * 
     * @throws \Exception
     * @return void
     * @throws \Throwable
     */
    public function runStartingIzyMigrations(): void
    {
        $connection = $this->config['dbType'];
        // controlla che esista la tabella migrations perchè la prima volta che vengono eseguite su un db
        // viene creata in automatico, perciò se è gia stato fatto non serve fare le migrazioni iniziali
        if (!Schema::connection($connection)->hasTable('migrations')) { 
            try {
                DB::connection($connection)->beginTransaction();
                Artisan::call('migrate:fresh', ['--database' => $connection, '--force' => true]);
                DB::connection($connection)->commit();
                Log::info("Migrazioni iniziali eseguite sul database {$connection}.");
            } catch (\Exception $e) {
                DB::connection($connection)->rollBack();
                Log::error("Errore durante l'esecuzione delle migrazioni: " . $e->getMessage());
            }
        }
    }

    /**
     * Migra la sessione attiva dal vecchio database al nuovo controllando che la migrazioni sia possibile.
     *
     * @param string $oldConnection
     * @param string $oldDbName
     * @param string $sessionId
     *
     * @throws \Exception
     */
    public function migrateActiveSession(string $oldConnection, string $oldDbName, string $sessionId): void
    {
        // controllo di casi di errore o in cui le migrazioni non siano necessarie
        if ($this->isSameDatabase($oldDbName)) {
            Log::info('Database vecchio e nuovo coincidono, migrazione sessioni non necessaria.');
            return;
        }
        if (!$this->canConnect($oldConnection) || !$this->canConnect($this->config['dbType'])) {
            Log::error('Connessione al database non attiva. Migrazione impossibile.');
            return;
        }
        try {
            $activeSession = $this->fetchSession($oldConnection, $sessionId);
            // controllo che esista la sessione da migrare
            if (!$activeSession) {
                Log::info('Nessuna sessione trovata nel vecchio database.');
                return;
            }
            $this->insertSession($this->config['dbType'], $activeSession);
            Log::info('Migrazione della sessione attiva completata con successo.');
        } catch (\Exception $e) {
            Log::error('Errore durante la migrazione della sessione: ' . $e->getMessage());
            throw $e;
        }
    }

    private function isSameDatabase(string $oldDbName): bool
    {
        return $oldDbName === $this->config['dbName'];
    }

    // recupera la sessione con id == sessionId nella tabella 'sessions'
    private function fetchSession(string $connection, string $sessionId): ?object
    {
        return DB::connection($connection)
            ->table('sessions')
            ->where('id', $sessionId)
            ->first();
    }

    private function insertSession(string $connection, object $session): void
    {
        DB::connection($connection)
            ->table('sessions')
            ->insert((array) $session);
    }

    /**
     * Verifica se una singola connessione al database è raggiungibile.
     *
     * @param string $connection
     * @return bool
     */
    private function canConnect(string $connection): bool
    {
        try {
            DB::connection($connection)->getPdo();
            return true;
        } catch (\Throwable $e) {
            Log::warning("Impossibile connettersi a [$connection]: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Esegue un seeder specifico.
     *
     * @param string $seederClass
     */
    protected function runSeeder(string $seederClass): void
    {
        try {
            Artisan::call('db:seed', ['--class' => $seederClass]);
            Log::info("Seeder {$seederClass} eseguito con successo.");
        } catch (\Exception $e) {
            Log::error("Errore eseguendo il seeder {$seederClass}: " . $e->getMessage());
        }
    }

    /**
     * Esegue il seeder dei permessi.
     */
    public function runPermissionSeeder(): void
    {
        $this->runSeeder('PermissionSeeder');
    }

    /**
     * Esegue il seeder dei permessi.
     */
    public function runPostTypeSeeder(): void
    {
        $this->runSeeder('PostTypeSeeder');
    }

    /**
     * Esegue il seeder dei ruoli.
     */
    public function runRoleSeeder(): void
    {
        $this->runSeeder('RoleSeeder');
    }

    /**
     * Aggiorna il cache con i valori di configurazione se il database è inizializzato.
     */
    public function setConfigCacheDbInit(): void
    {
        try {
            if ($this->config['initialized']) {
                Cache::put(
                    config('izy-cache-keys.db_configured'),
                    $this->config,
                    now()->addMinutes(60)
                );
                Log::info('Cache aggiornata con db_configured.');
            }
        } catch (\Exception $e) {
            Log::error('Errore in setConfigCacheDbInit', ['exception' => $e->getMessage()]);
            throw $e;
        }
    }
}
