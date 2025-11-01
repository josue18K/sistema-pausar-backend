<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Items DSI - Laboratorio 1
            [
                'codigo' => 'DSI-PC-001',
                'nombre' => 'Computadora de Escritorio HP',
                'descripcion' => 'Intel Core i5, 8GB RAM, 256GB SSD',
                'categoria_id' => 2, // Equipos Informáticos
                'laboratorio_id' => 1,
                'carrera_id' => 1,
                'estado' => 'activo',
                'fecha_adquisicion' => '2023-03-15',
                'responsable_id' => 3,
                'valor' => 2500.00,
            ],
            [
                'codigo' => 'DSI-MON-001',
                'nombre' => 'Monitor LED 24 pulgadas',
                'descripcion' => 'Monitor Full HD Samsung',
                'categoria_id' => 2,
                'laboratorio_id' => 1,
                'carrera_id' => 1,
                'estado' => 'activo',
                'fecha_adquisicion' => '2023-03-15',
                'responsable_id' => 3,
                'valor' => 600.00,
            ],
            [
                'codigo' => 'DSI-PROY-001',
                'nombre' => 'Proyector Epson',
                'descripcion' => 'Proyector 3500 lúmenes',
                'categoria_id' => 4, // Equipos Audiovisuales
                'laboratorio_id' => 1,
                'carrera_id' => 1,
                'estado' => 'activo',
                'fecha_adquisicion' => '2022-08-20',
                'responsable_id' => 3,
                'valor' => 1800.00,
            ],
            // Items Enfermería
            [
                'codigo' => 'ENF-CAM-001',
                'nombre' => 'Camilla Hospitalaria',
                'descripcion' => 'Camilla con ruedas y altura ajustable',
                'categoria_id' => 6, // Equipos Médicos
                'laboratorio_id' => 4,
                'carrera_id' => 2,
                'estado' => 'activo',
                'fecha_adquisicion' => '2023-01-10',
                'responsable_id' => 4,
                'valor' => 1200.00,
            ],
            [
                'codigo' => 'ENF-EST-001',
                'nombre' => 'Estetoscopio Littmann',
                'descripcion' => 'Estetoscopio profesional',
                'categoria_id' => 6,
                'laboratorio_id' => 4,
                'carrera_id' => 2,
                'estado' => 'activo',
                'fecha_adquisicion' => '2023-02-15',
                'responsable_id' => 4,
                'valor' => 450.00,
            ],
            // Mobiliario general
            [
                'codigo' => 'MOB-ESC-001',
                'nombre' => 'Escritorio de Oficina',
                'descripcion' => 'Escritorio de madera 1.20m',
                'categoria_id' => 1, // Mobiliario
                'laboratorio_id' => 1,
                'carrera_id' => 1,
                'estado' => 'activo',
                'fecha_adquisicion' => '2022-05-10',
                'responsable_id' => 3,
                'valor' => 350.00,
            ],
            [
                'codigo' => 'MOB-SIL-001',
                'nombre' => 'Silla Ergonómica',
                'descripcion' => 'Silla giratoria con respaldo alto',
                'categoria_id' => 1,
                'laboratorio_id' => 1,
                'carrera_id' => 1,
                'estado' => 'activo',
                'fecha_adquisicion' => '2022-05-10',
                'responsable_id' => 3,
                'valor' => 280.00,
            ],
            [
                'codigo' => 'DSI-ARD-001',
                'nombre' => 'Kit Arduino Uno',
                'descripcion' => 'Kit completo con sensores y actuadores',
                'categoria_id' => 5, // Herramientas Lab DSI
                'laboratorio_id' => 3,
                'carrera_id' => 1,
                'estado' => 'mantenimiento',
                'fecha_adquisicion' => '2023-06-20',
                'responsable_id' => 5,
                'valor' => 180.00,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
