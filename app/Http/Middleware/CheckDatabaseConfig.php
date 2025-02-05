<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\DatabaseConfigService;

class CheckDatabaseConfig
{
    protected $databaseConfigService;

    public function __construct(DatabaseConfigService $databaseConfigService)
    {
        $this->databaseConfigService = $databaseConfigService;
    }
    public function handle(Request $request, Closure $next)
    {
        static $filePath = storage_path('/izy-starter/izy-fallback-config.php');

        // Carica la configurazione dal file
        $fallbackconfig = include($filePath);

        // Recupera lo stato della cache
        $dbCacheConfig = Cache::get('db_configured', false);

        if ($dbCacheConfig != false) {
            if (config('database.default') != $dbCacheConfig['dbType']) {
                $this->databaseConfigService->connectNewConfig($dbCacheConfig);
            }
        }

        // Aggiungi un log per monitorare i valori di configurazione
        Log::info('Verifica configurazione database', [
            'db_configured' => Cache::get('db_configured', false),
            'initialized' => $fallbackconfig['initialized'] ?? 'non definito',
        ]);

        // Controlla se la rotta è di setup database
        if ($request->routeIs('setup.database') || $request->routeIs('setup.database.response')) {
            Log::info('Setup database in corso. Proseguo con la richiesta.');
            return $next($request);
        }

        // Se la cache non è configurata e la configurazione non è stata completata, reindirizza al setup
        if (!$dbCacheConfig && !$fallbackconfig['initialized']) {
            Log::warning('Configurazione del database non completata. Redirigo a setup.', [
                'db_configured' => $dbCacheConfig,
                'initialized' => $fallbackconfig['initialized'],
            ]);
            return redirect()->route('setup.database');
        }

        // Ritorna il controllo alla request
        return $next($request);
    }
}
