<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Creazione della tabella 'page_types' che memorizza i tipi di pagina
        Schema::create('page_types', function (Blueprint $table) {
            $table->id(); // ID univoco
            $table->string('name'); // Nome del tipo di pagina (es. 'post', 'product', 'archive', etc.)
            $table->string('slug')->unique(); // Slug unico per il tipo di pagina
            $table->boolean('has_archive')->default(false); // Se questo tipo ha una pagina archivio
            $table->boolean('public')->default(true); // Se il tipo di pagina è pubblico (visibile)
            $table->json('labels')->nullable(); // Etichette personalizzate per il tipo di pagina
            $table->boolean('supports_title')->default(true); // Se il tipo di pagina supporta un titolo
            $table->boolean('supports_content')->default(true); // Se il tipo di pagina supporta il contenuto
            $table->timestamps(); // Timestamp per created_at e updated_at
        });

        // Modifica della tabella 'pages' per aggiungere il riferimento al tipo di pagina
        Schema::table('pages', function (Blueprint $table) {
            // Aggiunge la colonna 'page_type_id' per legare la pagina al suo tipo
            $table->foreignId('page_type_id')->constrained('page_types')->after('id');
        });
    }

    public function down()
    {
        // Rimozione della colonna 'page_type_id' dalla tabella 'pages'
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['page_type_id']); // Rimuove la chiave esterna
            $table->dropColumn('page_type_id'); // Rimuove la colonna
        });

        // Eliminazione della tabella 'page_types'
        Schema::dropIfExists('page_types');
    }
};

