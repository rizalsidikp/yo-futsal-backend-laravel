<?php

use Illuminate\Database\Seeder;

class DetailTeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::Create();
        for($x = 0; $x < 5; $x++){
            DB::table('team')->insert([
                'team_id' => $faker->company,
                'user' => $faker->city,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
