<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('turnos')->Insert([
            ['nome'=>'Manhã','created_at'=>now(),'updated_at'=>now()],
            ['nome'=>'Tarde','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
