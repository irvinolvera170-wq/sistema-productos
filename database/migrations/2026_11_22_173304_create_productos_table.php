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
        Schema::create('productos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre')->unique();
    $table->text('descripcion');
    $table->foreignId('categoria_id')->constrained('categorias');
    $table->decimal('precio', 10, 2);
    $table->unsignedInteger('existencias')->default(0);
    $table->string('estado')->default('disponible');
    $table->timestamps();

    // Índices para que los filtros y el orden no recorran toda la tabla
    $table->index('precio');
    $table->index('estado');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
