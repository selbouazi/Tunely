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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('preferencias_combustible', 'instrumento_preferido');
            $table->renameColumn('tipo_conduccion', 'nivel_experiencia');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('instrumento_preferido', 'preferencias_combustible');
            $table->renameColumn('nivel_experiencia', 'tipo_conduccion');
        });
    }
};
