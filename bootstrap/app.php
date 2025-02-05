<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckDatabaseConfig;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aggiungi il tuo middleware personalizzato qui con un alias
        //$middleware->appendToGroup('web', App\Http\Middleware\CheckDatabaseConfig::class);
        $middleware->web(append: [
            App\Http\Middleware\CheckDatabaseConfig::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'dbsetup' => CheckDatabaseConfig::class, // Alias per il middleware del DB
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class, // Middleware per i ruoli
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class, // Middleware per i permessi
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class, // Ruolo o permesso
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gestisci le eccezioni, se necessario
    })
    ->create();
