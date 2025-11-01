<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarreraFactory extends Factory
{
    public function definition(): array
    {
        $carreras = [
            ['nombre' => 'Desarrollo de Sistemas de Información', 'abreviatura' => 'DSI'],
            ['nombre' => 'Enfermería Técnica', 'abreviatura' => 'ENF'],
            ['nombre' => 'Administración de Empresas', 'abreviatura' => 'ADM'],
            ['nombre' => 'Contabilidad', 'abreviatura' => 'CONT'],
        ];

        $carrera = $this->faker->randomElement($carreras);

        return [
            'nombre' => $carrera['nombre'],
            'abreviatura' => $carrera['abreviatura'],
            'descripcion' => $this->faker->sentence(10),
        ];
    }
}
