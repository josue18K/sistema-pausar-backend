<?php

namespace Database\Factories;

use App\Models\Consumible;
use App\Models\Item;
use App\Models\Laboratorio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovimientoFactory extends Factory
{
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['entrada', 'salida', 'traslado', 'baja', 'mantenimiento']);

        return [
            'item_id' => $this->faker->boolean(70) ? Item::factory() : null,
            'consumible_id' => null,
            'tipo' => $tipo,
            'cantidad' => $tipo === 'entrada' || $tipo === 'salida' ? $this->faker->numberBetween(1, 10) : null,
            'origen_id' => $tipo === 'traslado' ? Laboratorio::factory() : null,
            'destino_id' => $tipo === 'traslado' ? Laboratorio::factory() : null,
            'observaciones' => $this->faker->sentence(10),
            'usuario_id' => User::factory(),
        ];
    }
}
