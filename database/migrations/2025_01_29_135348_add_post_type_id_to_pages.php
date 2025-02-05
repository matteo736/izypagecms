<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            // Aggiungi la colonna 'post_type_id' per collegare ogni pagina al suo tipo
            $table->foreignId('post_type_id')
                ->constrained('page_types') // Riferisce alla tabella 'page_types'
                ->onDelete('cascade') // Se il tipo di post viene eliminato, elimina anche le relative pagine
                ->after('id'); // Posiziona la colonna dopo la colonna 'id'
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            // Rimuove la chiave esterna e la colonna 'post_type_id'
            $table->dropForeign(['post_type_id']);
            $table->dropColumn('post_type_id');
        });
    }
};

