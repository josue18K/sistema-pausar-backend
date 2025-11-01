<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Mobiliario', 'descripcion' => 'Mesas, sillas, escritorios, estantes'],
            ['nombre' => 'Equipos Informáticos', 'descripcion' => 'Computadoras, laptops, monitores, impresoras'],
            ['nombre' => 'Equipos de Red', 'descripcion' => 'Routers, switches, access points'],
            ['nombre' => 'Equipos Audiovisuales', 'descripcion' => 'Proyectores, pantallas, parlantes'],
            ['nombre' => 'Herramientas de Laboratorio DSI', 'descripcion' => 'Kits electrónicos, multímetros, osciloscopios'],
            ['nombre' => 'Equipos Médicos', 'descripcion' => 'Estetoscopios, tensiómetros, camillas'],
            ['nombre' => 'Material Didáctico', 'descripcion' => 'Pizarras, marcadores, material educativo'],
            ['nombre' => 'Consumibles de Oficina', 'descripcion' => 'Papel, tintas, útiles de escritorio'],
            ['nombre' => 'Consumibles Médicos', 'descripcion' => 'Guantes, gasas, alcohol, jeringas'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
