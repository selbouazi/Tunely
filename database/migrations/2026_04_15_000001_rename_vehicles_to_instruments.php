<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('vehicles', 'instruments');

        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn(['año', 'kilometros']);
            $table->enum('tipo', ['nuevo', 'usado'])->default('nuevo')->after('modelo');
            $table->decimal('precio_original', 10, 2)->nullable()->after('precio');
            $table->integer('stock')->default(1)->after('precio_original');
        });
    }

    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'precio_original', 'stock']);
            $table->integer('año');
            $table->integer('kilometros');
        });

        Schema::rename('instruments', 'vehicles');
    }
};
