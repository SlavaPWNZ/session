<?php

namespace App\Http\Controllers;

use App\Session;

class MainController extends Controller
{
    public function index()
    {
        if (!empty($_GET['date'])){
            $date_start =  $_GET['date'];
            $date_end =  date("Y-m-d", strtotime("+1 day", strtotime($date_start)));

            $opened_sessions = Session::where('login_time', '<=', $date_start)
                ->where('logout_time', '>=', $date_start)
                ->where(function($q) use ($date_end){
                    $q->where('logout_time', '<=', $date_end)
                        ->orWhereNull('logout_time');
                })
                ->get()
                ->toArray();
            foreach ($opened_sessions as $os) {
                if (!isset($online[$os['user_id']])) $online[$os['user_id']] = 0;
                $online[$os['user_id']]++;
            }
            // select * from `session` where `login_time` <= $date_start and `logout_time` >= $date_start and (`logout_time` <= $date_end or `logout_time` is null) group by `user_id`
            // Нашли сессии на 00:00 текущего дня онлайн

            $count_users = count($online); // Онлайн уникальных юзеров

            $sessions_today = Session::where('login_time', '>', $date_start)
                ->where('login_time', '<', $date_end)
                ->orWhere('logout_time', '<', $date_end)
                ->where('logout_time', '>', $date_start)
                ->orderBy('login_time', 'asc')
                ->get()
                ->toArray();
            // select * from `session` where `login_time` > $date_start and `login_time` < $date_end or `logout_time` < $date_end and `logout_time` > $date_start order by `login_time` asc
            // Нашли сессии, которые созданы сегодня, либо завершаться сегодня

            for ($second = 0; $second < 60*60*24; $second++){
                $current_date = date("Y-m-d H:i:s", strtotime("+$second seconds", strtotime($date_start)));
                $find = $this->in_array_r($current_date, $sessions_today);
                if ($find){
                    $x=1;
                }
                /*if (count($users) > $max){
                    $max = count($users);
                    $result[$second] = count($users);
                }*/
            }
            $times = [];

        }else{
            $times = [];
        }

        return view('main', ['times' => $times]);
    }

    public function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $k => $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $z = $this->in_array_r($needle, $item, $strict))) {
                if (!empty($z)) return $z;
                return [
                    'user_id' => $haystack['user_id'],
                    'type' => $k == 'login_time' ? 1 : -1,
                ];
            }
        }
        return false;
    }
}
