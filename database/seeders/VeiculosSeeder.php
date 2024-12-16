<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VeiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Matriculas = ['LDA 1111', 'LDA 2222', 'LDA 3333', 'LDA 4444'];
        $Modelos = 1;
        $i = 0; //contador
        $VIN = ['1HGCM82633A004123','1HGCM82633A004000','1HGCM82633A00222','1HGCM82633A111352'];

        foreach($Matriculas as $Matricula){
            DB::table('veiculos')->updateOrInsert(
                ['Matricula'=>$Matricula],
                ['Matricula'=>$Matricula, 'modelos_id'=>$Modelos,'capacidade'=>6,'escolas_id'=>1,'VIN'=>$VIN[$i], 'created_at'=>now(),'updated_at'=>now() ]
            );
            $Modelos++;
            $i++;
        }
    }
}
