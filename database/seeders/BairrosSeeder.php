<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BairrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bairros = ['golf 2', 'Kimbango'];

        foreach($bairros as $bairro){
            DB::table('bairros')->updateOrInsert(
                ['nome'=>$bairro],
                ['nome'=>$bairro,'distritos_id'=>1,'created_at'=>now(),'updated_at'=>now()],
            );
        }
    }
}
