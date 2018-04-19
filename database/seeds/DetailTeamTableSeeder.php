<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Team;
use Faker\Factory as Faker;

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
        $teams = Team::get();
        foreach ($teams as $team){
            for($y = 0; $y < 5; $y++){            
                DB::table('detail_team')->insert([
                    'team_id' => $team->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'owner' => false,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
            }
        }
    }
}
