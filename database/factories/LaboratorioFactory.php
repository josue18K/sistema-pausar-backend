<?php

namespace Database\Factories;

use App\Models\Carrera;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaboratorioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => 'Laboratorio ' . $this->faker->word() . ' ' . $this->faker->numberBetween(1, 5),
            'carrera_id' => Carrera::factory(),
            'responsable_id' => User::factory(),
            'ubicacion' => $this->faker->randomElement(['Piso 1', 'Piso 2', 'Piso 3']) . ' - ' . $this->faker->randomElement(['Edificio A', 'Edificio B', 'Edificio C']),
        ];
    }
}
