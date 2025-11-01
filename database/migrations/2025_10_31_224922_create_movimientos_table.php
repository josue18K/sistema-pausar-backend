<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('consumible_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'traslado', 'baja', 'mantenimiento']);
            $table->integer('cantidad')->nullable();
            $table->foreignId('origen_id')->nullable()->constrained('laboratorios')->nullOnDelete();
            $table->foreignId('destino_id')->nullable()->constrained('laboratorios')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
