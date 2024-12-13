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

        foreach($Matriculas as $Matricula){
            DB::table('veiculos')->updateOrInsert(
                ['Matricula'=>$Matricula],
                ['Matricula'=>$Matricula, 'modelos_id'=>$Modelos,'capacidade'=>6, 'created_at'=>now(),'updated_at'=>now() ]
            );
            $Modelos++;
        }
    }
}
