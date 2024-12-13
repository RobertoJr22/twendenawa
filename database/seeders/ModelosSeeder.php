<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelos = ['Pajero', 'X8', 'Rio', 'Maybach' ];
        $MarcasId = 1;

        foreach($modelos as $modelo){
            DB::table('modelos')->updateOrInsert(
                ['nome'=>$modelo],
                ['nome'=>$modelo, 'marcas_id'=>$MarcasId, 'created_at'=>now(),'updated_at'=>now()]
            );

            $MarcasId++;
        }
    }
}
