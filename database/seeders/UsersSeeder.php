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
        $emails = ['ulumbo@gmail.com'];

        foreach($emails as $email){
            DB::table('users')->updateOrInsert(
                ['email'=>$email],
                ['name'=>'Ulumbo','email'=>$email,'password'=>bcrypt('123456'),'tipo_usuario_id'=>5, 'created_at'=>now(), 'updated_at'=>now()]
            );
        }
    }
}
