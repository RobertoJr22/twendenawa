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
            ['nome' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'estudante', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'motorista', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Responsavel', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
