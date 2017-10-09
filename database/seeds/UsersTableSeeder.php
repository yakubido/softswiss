<?php

use Illuminate\Database\Seeder;
use App\User as User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();
        $password = Hash::make('test');

        for ($i = 10; $i > 0;  $i--) {
            User::create([
               'id' => $i,
               'name' => $faker->name,
               'email' => $faker->email,
               'password' => $password,
               'balance' => $faker->randomFloat(5, 100, 1000),
            ]);
        }
    }
}
