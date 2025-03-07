<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts'; // Definiamo il nome della tabella

    // Definiamo i campi che possono essere assegnati in massa
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'meta_keywords',
        'content',
        'status',
        'post_type_id',
        'author_id',
    ];

    // Cast per gestire il formato dei dati
    protected $casts = [
        'content' => 'array', // Converte il contenuto JSON in un array
    ];

    // Relazione con l'autore (utente)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Relazione con il tipo di post (post_type)
    public function post_type()
    {
        return $this->belongsTo(Post_Type::class, 'post_type_id');
    }
}
