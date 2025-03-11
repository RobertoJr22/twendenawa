<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'ulumbo@gmail.com'],
            [
                'name' => 'Colégio Ulumbo',
                'email' => 'ulumbo@gmail.com',
                'username'=>'Colégio_Ulumbo',
                'password' => bcrypt('000000'),
                'tipo_usuario_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Usuário admin (tipo_usuario_id = 1)
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@twendenawa.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@twendenawa.com',
                'password' => bcrypt('000000'),
                'username'=>'Admin_',
                'tipo_usuario_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
