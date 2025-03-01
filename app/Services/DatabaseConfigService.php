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
    public $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // Testa la connessione al database
    public function testConnection(): bool
    {
        try {
            $dsn = "{$this->config['dbType']}:host={$this->config['host']};port={$this->config['port']}";
            $pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            return true;
        } catch (PDOException $e) {
            Log::error('Database connection failed: ' . $e->getMessage());
            return false;
        }
    }

    public function connectConfig(): void
    {
        // 1. Imposta la nuova connessione come default
        config()->set('database.default', $this->config['dbType']); // Nuovo tipo di connessione
        // 2. Modifica le impostazioni della nuova connessione predefinita
        config()->set("database.connections.{$this->config['dbType']}", [
            'driver' => $this->config['dbType'],
            'host' => $this->config['host'],
            'database' => $this->config['dbName'],
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'port' => (int) $this->config['port'],
            // altre impostazioni, se necessarie
        ]);
        // 3. purga e riconnetti 
        DB::purge(config('database.default'));
        DB::reconnect(config('database.default'));
    }

    // Aggiorna la configurazione del database
    public function updateData(array $newConfig): void
    {
        $this->config = $newConfig;
        Log::info($this->config);
    }

    //aggiorna la configurazione e connettila
    public function connectNewConfig($newConfig): void
    {
        $this->updateData($newConfig);
        $this->connectConfig();
    }

    // Esegui le migrazioni iniziali solo se necessario
    public function runStartingIzyMigrations(): void
    {
        $connection = $this->config['dbType'];
        if (!Schema::connection($connection)->hasTable('migrations')) {
            try {
                DB::connection($connection)->beginTransaction();
                Artisan::call('migrate:fresh', ['--database' => $connection, '--force' => true]);
                DB::connection($connection)->commit();
            } catch (\Exception $e) {
                DB::connection($connection)->rollBack();
                Log::error("Errore durante l'esecuzione del comando: " . $e->getMessage());
            }
        }
    }

    public function migrateActiveSession($oldConnection, $sessionId): void
    {
        // Recupera il nome del database corrente
        $oldDbName = config("database.connections.$oldConnection.database");

        if ($oldDbName == $this->config['dbName']) {
            Log::info('Il nome del nuovo e del vecchio database è uguale, non serve migrare le sessioni');
            return;
        }

        try {
            // Verifica che entrambe le connessioni siano attive
            if (!DB::connection($oldConnection)->getPdo() || !DB::connection($this->config['dbType'])->getPdo()) {
                Log::error('Una delle connessioni al database non è attiva. Impossibile migrare la sessione.');
                return;
            }

            // Recupera la sessione attiva dal vecchio database
            $activeSession = DB::connection($oldConnection)->table('sessions')->where('id', $sessionId)->first();

            if ($activeSession) {
                // Inserisci la sessione attiva nel nuovo database
                DB::connection($this->config['dbType'])->table('sessions')->insert((array) $activeSession);
                Log::info('Migrazione della sessione attiva completata con successo.');
            } else {
                Log::info('Nessuna sessione attiva trovata nel vecchio database.');
            }
        } catch (\Exception $e) {
            Log::error('Errore durante la migrazione della sessione attiva: ' . $e->getMessage());
            throw $e;
        }
    }

    // Funzione che esegue il seeder dei permessi
    public function runPermissionSeeder(): void
    {
        try {
            // Esegui il seeder dei permessi
            Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);
            Log::info('Permissions seeder executed successfully.');
        } catch (\Exception $e) {
            Log::error('Error executing permission seeder: ' . $e->getMessage());
        }
    }

    // Funzione che esegue il seeder dei ruoli
    public function runRoleSeeder(): void
    {
        try {
            // Esegui il seeder dei ruoli
            Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
            Log::info('Role seeder executed successfully.');
        } catch (\Exception $e) {
            Log::error('Error executing role seeder: ' . $e->getMessage());
        }
    }

    public function setConfigCacheDbInit(): void
    {
        try {
            if ($this->config['initialized']) {
                Cache::put(config('izy-cache-keys.db_configured'), $this->config, now()->addMinutes(60));
                Log::info('Cache aggiornata con db_configured con i valori di configurazione');
            }
        } catch (\Exception $e) {
            Log::error('Errore in setTrueCacheDbInit', ['exception' => $e->getMessage()]);
            throw $e; // Rilancia l'errore se necessario
        }
    }
}
