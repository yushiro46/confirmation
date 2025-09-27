<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'tony',
            'email' => 'tony@example.com',
            'password' => Hash::make('pas123')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'jack',
            'email' => 'jack@example.com',
            'password' => Hash::make('pas456')
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'sara',
            'email' => 'sara@example.com',
            'password' => Hash::make('pas789')
        ];
        DB::table('users')->insert($param);
    }
}
