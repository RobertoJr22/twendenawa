<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = ['ulumbo@gmail.com', 'admin@twendenawa.com'];
        $i = 5;

        if($i == 5){
            foreach($emails as $email){
                DB::table('users')->updateOrInsert(
                    ['email'=>$email],
                    ['name'=>'Ulumbo','email'=>$email,'password'=>bcrypt('000000'),'tipo_usuario_id'=>$i, 'created_at'=>now(), 'updated_at'=>now()]
                );
                $i-=4;
            }
        }elseif($i == 1){
            foreach($emails as $email){
                DB::table('users')->updateOrInsert(
                    ['email'=>'admin@twendenawa.com'],
                    ['name'=>'Roberto Mbuta','email'=>'admin@twendenawa.com','password'=>bcrypt('000000'),'tipo_usuario_id'=>$i, 'created_at'=>now(), 'updated_at'=>now()]
                );
            }
        }
    }
}
