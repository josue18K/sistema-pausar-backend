<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsumibleFactory extends Factory
{
    public function definition(): array
    {
        $consumibles = [
            ['nombre' => 'Papel Bond A4', 'unidad' => 'paquete'],
            ['nombre' => 'Marcadores para Pizarra', 'unidad' => 'caja'],
            ['nombre' => 'Alcohol en Gel', 'unidad' => 'litro'],
            ['nombre' => 'Guantes Quirúrgicos', 'unidad' => 'caja'],
            ['nombre' => 'Gasas Estériles', 'unidad' => 'paquete'],
            ['nombre' => 'Toner para Impresora', 'unidad' => 'unidad'],
            ['nombre' => 'Cables HDMI', 'unidad' => 'unidad'],
            ['nombre' => 'Pilas AA', 'unidad' => 'paquete'],
        ];

        $consumible = $this->faker->randomElement($consumibles);

        return [
            'nombre' => $consumible['nombre'],
            'categoria_id' => Categoria::factory(),
            'stock' => $this->faker->numberBetween(0, 100),
            'stock_minimo' => $this->faker->numberBetween(5, 20),
            'unidad_medida' => $consumible['unidad'],
            'ubicacion' => 'Almacén ' . $this->faker->randomElement(['Principal', 'Secundario', 'DSI', 'Enfermería']),
            'responsable_id' => User::factory(),
        ];
    }
}
