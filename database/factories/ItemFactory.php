<?php

namespace Database\Factories;

use App\Models\Carrera;
use App\Models\Categoria;
use App\Models\Laboratorio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $items = [
            'Computadora de Escritorio',
            'Laptop',
            'Monitor LED',
            'Proyector',
            'Pizarra Acrílica',
            'Escritorio',
            'Silla Ergonómica',
            'Router',
            'Switch',
            'Impresora',
            'Mesa de Laboratorio',
            'Multímetro',
            'Osciloscopio',
            'Kit Arduino',
        ];

        return [
            'codigo' => 'ITEM-' . strtoupper($this->faker->unique()->bothify('???-####')),
            'nombre' => $this->faker->randomElement($items),
            'descripcion' => $this->faker->sentence(12),
            'categoria_id' => Categoria::factory(),
            'laboratorio_id' => Laboratorio::factory(),
            'carrera_id' => Carrera::factory(),
            'estado' => $this->faker->randomElement(['activo', 'activo', 'activo', 'mantenimiento', 'baja']),
            'fecha_adquisicion' => $this->faker->dateTimeBetween('-3 years', 'now'),
            'responsable_id' => User::factory(),
            'valor' => $this->faker->randomFloat(2, 100, 5000),
            'foto' => null,
        ];
    }
}
