<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key_name', 'value'];
}