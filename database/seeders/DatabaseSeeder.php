<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CarreraSeeder::class,
            CategoriaSeeder::class,
            UserSeeder::class,
            LaboratorioSeeder::class,
            ItemSeeder::class,
            ConsumibleSeeder::class,
        ]);
    }
}
