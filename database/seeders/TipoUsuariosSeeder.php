<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('tipo_usuarios')->insert([
            ['nome' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Estudante', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Motorista', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Responsavel', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Escola', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
