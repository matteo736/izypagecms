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
     * Testa la connessione al database.
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
        config()->set('database.default', $dbType);
        config()->set("database.connections.{$dbType}", $this->buildConnectionConfig());

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
     * Esegue le migrazioni iniziali se la tabella 'migrations' non esiste.
     */
    public function runStartingIzyMigrations(): void
    {
        $connection = $this->config['dbType'];
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
     * Migra la sessione attiva dal vecchio database al nuovo.
     *
     * @param string $oldConnection
     * @param string $oldDbName
     * @param mixed $sessionId
     *
     * @throws \Exception
     */
    public function migrateActiveSession(string $oldConnection, string $oldDbName, $sessionId): void
    {
        if ($oldDbName === $this->config['dbName']) {
            Log::info('Il nome del nuovo e del vecchio database è uguale, non serve migrare le sessioni.');
            return;
        }

        try {
            if (!$this->areConnectionsActive($oldConnection, $this->config['dbType'])) {
                Log::error('Una delle connessioni al database non è attiva. Impossibile migrare la sessione.');
                return;
            }

            $activeSession = DB::connection($oldConnection)
                ->table('sessions')
                ->where('id', $sessionId)
                ->first();

            if ($activeSession) {
                DB::connection($this->config['dbType'])
                    ->table('sessions')
                    ->insert((array) $activeSession);
                Log::info('Migrazione della sessione attiva completata con successo.');
            } else {
                Log::info('Nessuna sessione attiva trovata nel vecchio database.');
            }
        } catch (\Exception $e) {
            Log::error('Errore durante la migrazione della sessione attiva: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verifica se entrambe le connessioni sono attive.
     *
     * @param string $connectionA
     * @param string $connectionB
     *
     * @return bool
     */
    protected function areConnectionsActive(string $connectionA, string $connectionB): bool
    {
        try {
            DB::connection($connectionA)->getPdo();
            DB::connection($connectionB)->getPdo();
            return true;
        } catch (\Exception $e) {
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
            if (!empty($this->config['initialized'])) {
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
