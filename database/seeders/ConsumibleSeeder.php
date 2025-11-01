<?php

namespace Database\Seeders;

use App\Models\Consumible;
use Illuminate\Database\Seeder;

class ConsumibleSeeder extends Seeder
{
    public function run(): void
    {
        $consumibles = [
            [
                'nombre' => 'Papel Bond A4',
                'categoria_id' => 8, // Consumibles de Oficina
                'stock' => 50,
                'stock_minimo' => 20,
                'unidad_medida' => 'paquete',
                'ubicacion' => 'Almacén Principal',
                'responsable_id' => 2,
            ],
            [
                'nombre' => 'Marcadores para Pizarra',
                'categoria_id' => 7, // Material Didáctico
                'stock' => 8,
                'stock_minimo' => 15,
                'unidad_medida' => 'caja',
                'ubicacion' => 'Almacén Principal',
                'responsable_id' => 2,
            ],
            [
                'nombre' => 'Alcohol en Gel',
                'categoria_id' => 9, // Consumibles Médicos
                'stock' => 25,
                'stock_minimo' => 10,
                'unidad_medida' => 'litro',
                'ubicacion' => 'Almacén Enfermería',
                'responsable_id' => 4,
            ],
            [
                'nombre' => 'Guantes Quirúrgicos',
                'categoria_id' => 9,
                'stock' => 5,
                'stock_minimo' => 20,
                'unidad_medida' => 'caja',
                'ubicacion' => 'Almacén Enfermería',
                'responsable_id' => 4,
            ],
            [
                'nombre' => 'Gasas Estériles',
                'categoria_id' => 9,
                'stock' => 30,
                'stock_minimo' => 15,
                'unidad_medida' => 'paquete',
                'ubicacion' => 'Tópico',
                'responsable_id' => 4,
            ],
            [
                'nombre' => 'Toner HP Negro',
                'categoria_id' => 8,
                'stock' => 3,
                'stock_minimo' => 5,
                'unidad_medida' => 'unidad',
                'ubicacion' => 'Almacén Principal',
                'responsable_id' => 2,
            ],
            [
                'nombre' => 'Cables HDMI',
                'categoria_id' => 4, // Equipos Audiovisuales
                'stock' => 12,
                'stock_minimo' => 8,
                'unidad_medida' => 'unidad',
                'ubicacion' => 'Almacén DSI',
                'responsable_id' => 3,
            ],
        ];

        foreach ($consumibles as $consumible) {
            Consumible::create($consumible);
        }
    }
}
