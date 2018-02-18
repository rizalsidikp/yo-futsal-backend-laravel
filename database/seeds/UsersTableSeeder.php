<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $faker = Faker::Create();
        for($x = 0; $x < 20; $x++){
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('user-player'),
                'birthdate' => $faker->date,
                'gender' => 'male',
                'phone_number' => $faker->phoneNumber,
                'photo' => '',
                'scope' => 'player',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
        for($x = 0; $x < 10; $x++){
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('user-field'),
                'birthdate' => $faker->date,
                'gender' => 'male',
                'phone_number' => $faker->phoneNumber,
                'photo' => '',
                'scope' => 'field',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
