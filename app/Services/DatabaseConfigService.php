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
            $dsn = "{$this->config['dbType']}:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['dbName']}";
            $pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            Log::info('Connection is successful!');
            return true;
        } catch (PDOException $e) {
            Log::error('Database connection failed: ' . $e->getMessage(), [
                'code' => $e->getCode(),
                'dsn' => $dsn,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return false;
        }
    }

    public function connectConfig(): void
    {
        // 2. Imposta la nuova connessione come default
        config()->set('database.default', $this->config['dbType']); // Nuovo tipo di connessione
        // 3. Modifica le impostazioni della nuova connessione predefinita
        config()->set("database.connections.{$this->config['dbType']}", [
            'driver' => $this->config['dbType'],
            'host' => $this->config['host'],
            'database' => $this->config['dbName'],
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'port' => (int) $this->config['port'],
            // altre impostazioni, se necessarie
        ]);
        //purga e riconnetti 
        DB::purge(config('database.default'));
        DB::reconnect(config('database.default'));
    }

    // Aggiorna la configurazione del database
    public function updateData(array $newConfig): void
    {
        $this->config = $newConfig;
        Log::info('Updated data in DatabaseConfigService --> pwd: ' . $this->config['password']);
    }

    //aggiorna la configurazione e connettila
    public function connectNewConfig($newConfig): void
    {
        $this->updateData($newConfig);
        $this->connectConfig();
    }

    // Esegui le migrazioni solo se necessario
    public function runStartingIzyMigrations(): void
    {
        Log::info('entra in runmigrations');
        $connection = $this->config['dbType'];
        if (!Schema::connection($connection)->hasTable('migrations')) {
            try {
                // Esegui la migrazione
                Artisan::call('migrate:fresh', ['--database' => $connection, '--force' => true]);
            } catch (\Exception $e) {
                Log::error("Errore durante l'esecuzione del comando: " . $e->getMessage());
            }
            Log::debug("Completato il blocco try di migrations.");
        }
    }

    public function migrateOldSession($oldConnection): void
    {
        if ($oldConnection == $this->config['dbType']) {
            Log::info('i driver della nuova e della vecchia configurazione sono uguali, non serve migrare le sessioni');
        } else {
            $oldSessions = DB::connection($oldConnection)->table('sessions')->get();

            DB::connection($this->config['dbType'])->table('sessions')->insert(
                $oldSessions->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'user_id' => $session->user_id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'payload' => $session->payload,
                        'last_activity' => $session->last_activity,
                    ];
                })->toArray()
            );
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
        Log::info('Entrato in setTrueCacheDbInit');
        // Forza un output debug per verificare l'esecuzione
        //dd('Debug: Sono dentro la funzione setTrueCacheDbInit');
        try {
            Log::info('Driver cache corrente: ' . config('cache.default'));
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
