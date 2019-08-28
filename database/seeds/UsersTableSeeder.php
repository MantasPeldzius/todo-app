<?php

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
    public function run() {
    	DB::table('users')->insert([
    		'user' => 'admin',
    		'password' => Hash::make('admin'),
    		'email' => 'admin@localhost',
    		'role' => 1,
    		'created_at' => date('Y-m-d H:i:s'),
    		'updated_at' => date('Y-m-d H:i:s'),
    	]);
    	DB::table('users')->insert([
    		'user' => 'user',
    		'password' => Hash::make('user'),
    		'email' => 'user@localhost',
    		'created_at' => date('Y-m-d H:i:s'),
    		'updated_at' => date('Y-m-d H:i:s'),
    	]);
    }
}
