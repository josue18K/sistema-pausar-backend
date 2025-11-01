<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Josue Ramos Neira',
            'email' => 'admin@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
            'carrera_id' => 1, // DSI
        ]);

        // Responsable de Almacén
        User::create([
            'name' => 'María González',
            'email' => 'almacen@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'almacen',
            'carrera_id' => null,
        ]);

        // Responsable de Carrera DSI
        User::create([
            'name' => 'Carlos Pérez',
            'email' => 'responsable.dsi@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'responsable',
            'carrera_id' => 1, // DSI
        ]);

        // Responsable de Carrera Enfermería
        User::create([
            'name' => 'Ana Martínez',
            'email' => 'responsable.enf@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'responsable',
            'carrera_id' => 2, // ENF
        ]);

        // Docente DSI
        User::create([
            'name' => 'Luis Torres',
            'email' => 'docente.dsi@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'docente',
            'carrera_id' => 1, // DSI
        ]);

        // Docente Enfermería
        User::create([
            'name' => 'Rosa Flores',
            'email' => 'docente.enf@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'docente',
            'carrera_id' => 2, // ENF
        ]);

        // Auditor
        User::create([
            'name' => 'Jorge Sánchez',
            'email' => 'auditor@instituto.edu.pe',
            'password' => Hash::make('password123'),
            'rol' => 'auditor',
            'carrera_id' => null,
        ]);
    }
}
