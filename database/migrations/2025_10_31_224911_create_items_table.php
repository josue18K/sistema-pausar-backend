<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laboratorio_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('carrera_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('estado', ['activo', 'mantenimiento', 'baja'])->default('activo');
            $table->date('fecha_adquisicion')->nullable();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
