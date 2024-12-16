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
            ['nome'=>'Manhã','HoraIda'=>'07:00:00','HoraRegresso'=>'13:00:00','created_at'=>now(),'updated_at'=>now()],
            ['nome'=>'Tarde','HoraIda'=>'12:00:00','HoraRegresso'=>'18:00:00','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
