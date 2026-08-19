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
        Schema::create('gasto', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad',8,2);
            $table->decimal('monto',8,2);
            $table->string('telegram_user_id');
            $table->foreignId('producto_id')->constrained('producto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto');
    }
};
