<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$m2BNl30cZMbSNeJmWerl4Oe7qfiSBVmg8t5JCTmLMJEvoIBqJGMze',
            'is_admin' => '1',
            'status' => '0',
            'remember_token' => str_random(10),
        ]);
    }
}
