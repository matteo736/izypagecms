<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Setup\SetupDBController;
use App\Http\Controllers\Content\Posts\PostsController;
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
    Route::prefix('Pages')->group(function () {
        Route::get('/all', [PostsController::class, 'index'])
        ->name('pages');
        Route::get('/{id}', [PostsController::class, 'single'])
        ->name('page');
    });
});