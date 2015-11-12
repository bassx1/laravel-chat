<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $faker = Faker\Factory::create();

        //Create users
        $emails = ['test@aa.com','test2@aa.com', 'phpdimas@gmail.com'];
        foreach($emails as $key => $email) {
            User::create(['email' => $email, 'password' => bcrypt(1), 'name' => "User_".$key, 'role' => User::USER]);
        }

        // Create admin
        User::create(['email' => 'admin@aa.com', 'password' => bcrypt(1), 'name' => "Admin", 'role' => User::ADMIN ]);
    }
}
