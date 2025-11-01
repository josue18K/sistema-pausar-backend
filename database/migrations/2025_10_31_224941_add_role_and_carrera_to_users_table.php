<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'almacen', 'responsable', 'docente', 'auditor'])->default('docente')->after('password');
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->nullOnDelete()->after('rol');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['carrera_id']);
            $table->dropColumn(['rol', 'carrera_id']);
        });
    }
};
