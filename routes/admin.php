<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Setup\SetupDBController;
use App\Http\Controllers\Content\Posts\PostsController;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
| It contains the routes for the setup of the database for the website
| and the routes for the admin panel of the application.
|
|--------------------------------------------------------------------------
*/

////setup--database
Route::middleware('guest')->group(function () {
    Route::get('/setup/setupdb', [SetupDBController::class, 'render'])
        ->name('setup.database');

    Route::post('/setup/setupdb', [SetupDBController::class, 'setconfig'])
        ->name('setup.database.response');
});
/////--------------

////izy-administration
Route::middleware('auth')->prefix('izyAdmin')->group(function () {
    // prefisso per le rotte che riguardano le pagine
    Route::prefix('Pages')->group(function () {
        // mostra tutte le pagine
        Route::get('/all', [PostsController::class, 'index'])
            ->name('pages.all');

        // mostra una pagina specifica
        Route::get('/new', [PostsController::class, 'newSinglePage'])
            ->name('page.new');

        // rotta per la creazione di una nuova pagina
        Route::post('store', [PostsController::class, 'store'])
            ->name('page.store');

        // mostra una pagina specifica
        Route::get('/{id}', [PostsController::class, 'single'])
            ->name('page.view');

        // rotta per l'eliminazione di una pagina
        Route::delete('delete/{id}', [PostsController::class, 'destroy'])
            ->name('page.delete');

        // rotta per l'aggiornamento di una pagina
        Route::put('update/{id}', [PostsController::class, 'update'])
            ->name('page.update');
    });
});

