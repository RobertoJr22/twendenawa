<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EscolasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $escolas = ['Ulumbo'];

        foreach($escolas as $escola){
            DB::table('escolas')->updateOrInsert(
                ['nome'=>$escola],
                ['id'=>1,'nome'=>$escola,'telefone'=>924162800,'bairros_id'=>1,'created_at'=>now(),'updated_at'=>now()]
            );
        }
    }
}
