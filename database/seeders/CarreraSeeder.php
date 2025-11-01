<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = [
            [
                'nombre' => 'Desarrollo de Sistemas de Información',
                'abreviatura' => 'DSI',
                'descripcion' => 'Carrera técnica enfocada en el desarrollo de software y sistemas informáticos',
            ],
            [
                'nombre' => 'Enfermería Técnica',
                'abreviatura' => 'ENF',
                'descripcion' => 'Carrera técnica en el área de salud y cuidados de enfermería',
            ],
            [
                'nombre' => 'Administración de Empresas',
                'abreviatura' => 'ADM',
                'descripcion' => 'Carrera técnica en gestión empresarial y administración',
            ],
            [
                'nombre' => 'Contabilidad',
                'abreviatura' => 'CONT',
                'descripcion' => 'Carrera técnica en contabilidad y finanzas',
            ],
        ];

        foreach ($carreras as $carrera) {
            Carrera::create($carrera);
        }
    }
}
