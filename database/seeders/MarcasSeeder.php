<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Marcas = ['Toyota', 'BMW', 'Kia', 'Mercedes'];

        foreach($Marcas as $Marca){
            DB::table('marcas')->updateOrInsert(
                ['nome'=>$Marca],
                ['nome'=>$Marca,'created_at'=>now(),'updated_at'=>now()],
            );
        }
    }
}
