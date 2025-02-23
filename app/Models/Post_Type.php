<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post_Type extends Model
{
    // Specifica il nome della tabella se non segue la convenzione (singolare del nome del modello in minuscolo e con "s")
    protected $table = 'post_types';

    // I campi che possono essere assegnati in massa
    protected $fillable = [
        'name',
        'slug',
        'has_archive',
        'public',
        'labels',
        'supports_title',
        'supports_content',
    ];

    // Cast dei campi, utile per trasformare automaticamente in boolean o array
    protected $casts = [
        'has_archive'       => 'boolean',
        'public'            => 'boolean',
        'supports_title'    => 'boolean',
        'supports_content'  => 'boolean',
        // Presupponendo che "labels" sia un campo JSON che contiene un array di etichette
        'labels'            => 'array',
    ];
}
