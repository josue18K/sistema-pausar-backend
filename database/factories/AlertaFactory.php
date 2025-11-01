<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertaFactory extends Factory
{
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['stock_bajo', 'mantenimiento', 'baja']);

        $mensajes = [
            'stock_bajo' => 'Stock bajo de ' . $this->faker->word() . '. Se requiere reposición.',
            'mantenimiento' => 'El equipo ' . $this->faker->word() . ' requiere mantenimiento programado.',
            'baja' => 'Se ha dado de baja el ítem ' . $this->faker->word() . '.',
        ];

        return [
            'tipo' => $tipo,
            'mensaje' => $mensajes[$tipo],
            'leido' => $this->faker->boolean(30),
            'usuario_id' => User::factory(),
        ];
    }
}
