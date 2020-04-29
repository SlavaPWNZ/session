<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 1000; $i++){
            $login_time = mt_rand(1587945600,1588118400); // 27.04 - 28.04
            $percent = rand(1, 4);
            if ($percent == 1) {
                $logout_time = null;
            }else{
                $logout_time = date("Y-m-d H:i:s", $login_time + 60*60*5); // + 5 часов
            }
            DB::table('session')->insert([
                'user_id' => rand(1, 10),
                'login_time' => date("Y-m-d H:i:s", $login_time),
                'logout_time' => $logout_time
            ]);
        }
    }
}
