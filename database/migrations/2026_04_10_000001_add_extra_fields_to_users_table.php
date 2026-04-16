<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('role');
            $table->string('telefono', 20)->nullable()->after('fecha_nacimiento');
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('ciudad')->nullable()->after('direccion');
            $table->string('provincia')->nullable()->after('ciudad');
            $table->string('codigo_postal', 10)->nullable()->after('provincia');
            $table->string('direccion_facturacion')->nullable()->after('codigo_postal');
            $table->string('ciudad_facturacion')->nullable()->after('direccion_facturacion');
            $table->string('provincia_facturacion')->nullable()->after('ciudad_facturacion');
            $table->string('codigo_postal_facturacion', 10)->nullable()->after('provincia_facturacion');
            $table->string('preferencias_combustible')->nullable()->after('codigo_postal_facturacion');
            $table->string('tipo_conduccion')->nullable()->after('preferencias_combustible');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_nacimiento',
                'telefono',
                'direccion',
                'ciudad',
                'provincia',
                'codigo_postal',
                'direccion_facturacion',
                'ciudad_facturacion',
                'provincia_facturacion',
                'codigo_postal_facturacion',
                'preferencias_combustible',
                'tipo_conduccion',
            ]);
        });
    }
};
