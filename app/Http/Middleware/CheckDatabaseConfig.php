<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\DatabaseConfigService;

/*
--------------------------------------------------------------------------------
- Middleware per verificare che la configurazione del db sia stata completata
--------------------------------------------------------------------------------
*/

class CheckDatabaseConfig
{
    protected $databaseConfigService;

    public function __construct(DatabaseConfigService $databaseConfigService)
    {
        $this->databaseConfigService = $databaseConfigService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Recupera lo stato della cache
        $dbCacheConfig = Cache::get('db_configured', false);
        
        // Controlla se la rotta è di setup database
        if ($request->routeIs('setup.database') || $request->routeIs('setup.database.response')) {
            return $next($request);
        }
        // Se la cache non è configurata e la configurazione non è stata completata, reindirizza al setup
        if (!$dbCacheConfig && !$this->databaseConfigService->getConfig()['initialized']) {
            return redirect()->route('setup.database');
        }

        return $next($request);
    }
}
