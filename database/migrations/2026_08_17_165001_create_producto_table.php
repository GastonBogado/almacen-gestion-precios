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
        Schema::create('producto', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('unidad');
    $table->decimal('precio_actual', 8, 2);
    $table->decimal('margen_pct', 5, 2)->default(40.00);
    $table->decimal('precio_venta_calculado', 8, 2);
    $table->foreignId('categoria_id')->constrained('categoria');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
