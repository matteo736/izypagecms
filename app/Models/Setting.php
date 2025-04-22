<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class Setting extends Model
{
    use HasFactory;
    /**
     * La tabella associata al modello.
     *
     * @var string
     */
    protected $table = 'settings';

    protected $fillable = ['key_name', 'value'];
}