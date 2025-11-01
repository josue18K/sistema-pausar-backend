<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumibles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(10);
            $table->string('unidad_medida', 50)->default('unidad');
            $table->string('ubicacion', 100)->nullable();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumibles');
    }
};
