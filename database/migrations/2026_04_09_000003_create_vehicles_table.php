<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('modelo');
            $table->integer('año');
            $table->integer('kilometros');
            $table->decimal('precio', 10, 2);
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
