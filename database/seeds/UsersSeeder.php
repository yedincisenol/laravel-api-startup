<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'      => 'Api Startup',
            'email'     => 'admin@apistartup.yedincisenol.com', // Should be change for production
            'role'      => 'admin',
            'is_active' => true,
            'password'  => bcrypt('123456'),
        ]);
    }
}
