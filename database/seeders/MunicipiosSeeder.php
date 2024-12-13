<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Municipios = ['K.Kiaxi', 'Maianga', 'Viana'];

        foreach($Municipios as $municipio){
            DB::table('Municipios')->updateOrInsert(
                ['nome'=>$municipio],
                ['nome'=>$municipio, 'created_at'=>now(), 'updated_at'=>now()]
            );
        }
    }
}
