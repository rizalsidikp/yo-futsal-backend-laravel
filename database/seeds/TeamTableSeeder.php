<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\User;

class TeamTableSeeder extends Seeder
{
    public function generateTeamId(){
        $prefix = 'YOF';
        $characters = 'QWERTYUIOPASDFGHJKLZXCVBNM0123456789';
        $max = strlen($characters) - 1;
        $generator = '';
        do{
            for ($i = 0; $i < 7; $i++) {
                $generator .= $characters[mt_rand(0, $max)];
            }
            $generator = $prefix.$generator;
        }while(DB::table('team')->select('id')->where('id', $generator)->get()->first());
        return $generator;
    }
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
                'id' => $this->generateTeamId(),
                'team_name' => $faker->company,
                'team_city' => $faker->city,
                'user_id' => User::inRandomOrder()->first()->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
