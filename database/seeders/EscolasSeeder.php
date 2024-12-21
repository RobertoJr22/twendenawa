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
        $ids = ['1'];

        foreach($ids as $id){
            DB::table('escolas')->updateOrInsert(
                ['users_id'=>$id],
                ['users_id'=>$id,'telefone'=>924162800,'bairros_id'=>1,'created_at'=>now(),'updated_at'=>now()]
            );
        }
    }
}
