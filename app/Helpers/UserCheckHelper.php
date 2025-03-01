<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserCheckHelper
{
    /**
     * Verifica se la tabella degli utenti è popolata
     *
     * @return bool
     */
    public static function userPopulated(): bool
    {
        $dbinfo = self::logDatabaseInfo();
        $fallback = include storage_path('/izy-starter/izy-fallback-config.php');
        $cacheConfig = Cache::get(config('izy-cache-keys.db_configured'), $fallback);
        if ($cacheConfig['dbType'] !== $dbinfo['driver']) {
            return DB::connection($cacheConfig['dbType'])->table('users')->count() > 0;
        } else {
            return User::count() > 0;
        }
    }

    /**
     * Verifica se l'admin si sta registrando
     *
     * @return bool
     */
    public static function adminRegistering(): bool
    {
        $dbinfo = self::logDatabaseInfo();
        $fallback = include storage_path('/izy-starter/izy-fallback-config.php');
        $cacheConfig = Cache::get(config('izy-cache-keys.db_configured'), $fallback);

        if ($cacheConfig['dbType'] !== $dbinfo['driver']) {
            return DB::connection($cacheConfig['dbType'])->table('users')->count() == 1;
        } else {
            return User::count() == 1;
        }
    }

    public static function logDatabaseInfo()
    {
        // Ottieni il nome del driver e del database attivo
        $connectionName = DB::getDefaultConnection();
        $driver = DB::connection()->getDriverName();
        $currentDatabase = DB::connection()->getDatabaseName();

        // Logga le informazioni
        Log::info('Informazioni sul database', [
            'connection' => $connectionName,
            'driver' => $driver,
            'database' => $currentDatabase,
        ]);

        // Puoi anche restituire le informazioni, se necessario
        return [
            'connection' => $connectionName,
            'driver' => $driver,
            'database' => $currentDatabase,
        ];
    }
}
