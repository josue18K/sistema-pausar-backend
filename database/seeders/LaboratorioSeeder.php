<?php

namespace Database\Seeders;

use App\Models\Laboratorio;
use Illuminate\Database\Seeder;

class LaboratorioSeeder extends Seeder
{
    public function run(): void
    {
        $laboratorios = [
            [
                'nombre' => 'Laboratorio de Computación 1',
                'carrera_id' => 1, // DSI
                'responsable_id' => 3, // Carlos Pérez
                'ubicacion' => 'Piso 2 - Edificio A',
            ],
            [
                'nombre' => 'Laboratorio de Computación 2',
                'carrera_id' => 1, // DSI
                'responsable_id' => 3,
                'ubicacion' => 'Piso 2 - Edificio A',
            ],
            [
                'nombre' => 'Laboratorio de Electrónica',
                'carrera_id' => 1, // DSI
                'responsable_id' => 5, // Luis Torres
                'ubicacion' => 'Piso 3 - Edificio A',
            ],
            [
                'nombre' => 'Laboratorio de Enfermería 1',
                'carrera_id' => 2, // ENF
                'responsable_id' => 4, // Ana Martínez
                'ubicacion' => 'Piso 1 - Edificio B',
            ],
            [
                'nombre' => 'Laboratorio de Enfermería 2',
                'carrera_id' => 2, // ENF
                'responsable_id' => 6, // Rosa Flores
                'ubicacion' => 'Piso 1 - Edificio B',
            ],
            [
                'nombre' => 'Tópico Institucional',
                'carrera_id' => 2, // ENF
                'responsable_id' => 4,
                'ubicacion' => 'Piso 1 - Edificio Principal',
            ],
        ];

        foreach ($laboratorios as $laboratorio) {
            Laboratorio::create($laboratorio);
        }
    }
}
