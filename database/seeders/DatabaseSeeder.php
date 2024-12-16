<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoUsuariosSeeder::class, 
            UsersSeeder::class,
            SexosSeeder::class,
            TurnosSeeder::class,
            MarcasSeeder::class,
            ModelosSeeder::class,
            MunicipiosSeeder::class,
            distritosSeeder::class,
            BairrosSeeder::class,
            EscolasSeeder::class,
            VeiculosSeeder::class,
        ]);
    }
}
