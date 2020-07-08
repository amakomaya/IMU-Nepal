<?php

use Illuminate\Database\Seeder;

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
        	'token'=>uniqid().sha1(1),
            'username' => 'amakomaya',
            'email' => 'amakomaya@gmail.com',
            'password' => md5('test123'),
            'role'=>'main',
            'created_at'=> date("Y-m-d h:m:s"),
            'updated_at'=> date("Y-m-d h:m:s")
        ]);
    }
}
