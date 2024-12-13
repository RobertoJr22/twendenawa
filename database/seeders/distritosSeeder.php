<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class distritosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distritos = ['golf', 'Palanca', 'Nova-Vida', 'Sapu'];
        
        foreach($distritos as $distrito){
            DB::table('distritos')->updateOrInsert(
                ['nome'=>$distrito],
                ['nome'=>$distrito, 'municipios_id'=>1, 'created_at'=>now(),'updated_at'=>now()]
            );
        }
    }
}
