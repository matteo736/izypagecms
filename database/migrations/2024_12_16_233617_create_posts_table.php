<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titolo della pagina
            $table->string('slug')->unique(); // Slug univoco per l'URL
            $table->json('content')->nullable(); // Contenuto della pagina
            $table->string('meta_description')->nullable(); // Meta descrizione
            $table->string('meta_keywords')->nullable(); // Meta parole chiave
            $table->enum('status', ['draft', 'published'])->default('draft'); // Stato della pagina
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); // Autore (chiave esterna verso `users`)
            $table->integer('parent_id')->nullable(); // Gerarchia per pagine nidificate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
