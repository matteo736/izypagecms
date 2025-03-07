<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Models\Post;

// Rotta per la pagina iniziale
Route::get('izyAdmin', function () {
    return Inertia::render('Welcome', [
        'title' => 'Welcome',
    ]);
})->middleware(['auth'])
->name('izy.admin');

Route::get('/', function () {
    return Inertia::render('Themes/izy-helloTheme/pageModels/StaticPageModel', [
        'title' => 'Welcome',
    ]);
});

include base_path('routes/auth.php');
include base_path('routes/admin.php');

/*
- rotta catch-all posizionata al fondo, in questo modo tutte le altre rotte predefinite per il
- CMS verranno associate per prime rispetto a quelle dedicate al sito dell'utente
*/ 
Route::get('{slug}', function ($slug) {
    $page = Post::where('slug', $slug)->firstOrFail();
    $activeTheme = Setting::where('key_name', 'active_theme')->first()->value;
    return Inertia::render('Themes/' . $activeTheme . 'pageModels/', ['content' => $page->content]);
})->where('slug', '.*');