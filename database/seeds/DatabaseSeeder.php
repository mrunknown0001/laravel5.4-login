<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
        	'id_no' => '00000000',
        	'username' => 'admin',
        	'first_name' => 'Admin',
        	'email' => 'admin@localhost',
        	'password' => bcrypt('password'),
        	'active' => 1,
        	]);

        DB::table('password_histories')->insert([
            'user_id_no' => '00000000',
            'password' => bcrypt('password'),
            ]);
    }
}
