<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    // Definiamo i campi che possono essere assegnati in massa
    protected $fillable = [
        'title', 
        'slug', 
        'meta_description', 
        'content', 
        'status', 
        'author_id', 
        'published_at'
    ];

    // Cast per gestire il formato dei dati
    protected $casts = [
        'content' => 'array', // Converte il contenuto JSON in un array
        'published_at' => 'datetime', // Converte la data di pubblicazione
    ];

    // Relazione con l'autore (utente)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
