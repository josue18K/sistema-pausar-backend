<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        $categorias = [
            'Mobiliario',
            'Equipos Informáticos',
            'Herramientas',
            'Material de Laboratorio',
            'Equipos Médicos',
            'Material Didáctico',
            'Consumibles de Oficina',
            'Equipos Audiovisuales',
        ];

        return [
            'nombre' => $this->faker->unique()->randomElement($categorias),
            'descripcion' => $this->faker->sentence(8),
        ];
    }
}
