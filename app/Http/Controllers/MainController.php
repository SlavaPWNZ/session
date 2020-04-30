<?php

namespace App\Http\Controllers;

use App\Session;

class MainController extends Controller
{
    public function index($date)
    {
        if ($date){
            $date_start =  $date;
            $date_end =  date("Y-m-d", strtotime("+1 day", strtotime($date_start)));

            $opened_sessions = Session::where('login_time', '<=', $date_start)
                ->where('logout_time', '>=', $date_start)
                ->where(function($q) use ($date_end){
                    $q->where('logout_time', '<=', $date_end)
                        ->orWhereNull('logout_time');
                })
                ->get()
                ->toArray();
            $online = [];
            foreach ($opened_sessions as $os) {
                if (!isset($online[$os['user_id']])) $online[$os['user_id']] = 0;
                $online[$os['user_id']]++;
            }
            // select * from `session` where `login_time` <= $date_start and `logout_time` >= $date_start and (`logout_time` <= $date_end or `logout_time` is null) group by `user_id`
            // Нашли сессии на 00:00 текущего дня онлайн

            $max_users = count($online); // Онлайн уникальных юзеров
            $sessions_today = Session::where('login_time', '>', $date_start)
                ->where('login_time', '<', $date_end)
                ->orWhere('logout_time', '<', $date_end)
                ->where('logout_time', '>', $date_start)
                ->orderBy('login_time', 'asc')
                ->get()
                ->toArray();
            // select * from `session` where `login_time` > $date_start and `login_time` < $date_end or `logout_time` < $date_end and `logout_time` > $date_start order by `login_time` asc
            // Нашли сессии, которые созданы сегодня, либо завершаться сегодня
            $times = [];
            foreach ($sessions_today as $st){
                $times[$st['login_time']][$st['user_id']] = 1;

                if (isset($times[$st['logout_time']][$st['user_id']]) && $times[$st['logout_time']][$st['user_id']] == 1){
                    $times[$st['logout_time']][$st['user_id']] = 0; // Если в одну секунду может быть логин и разлогин
                }else{
                    $times[$st['logout_time']][$st['user_id']] = -1;
                }
            }
            if (!empty($times)) ksort($times);

            $check = 0;
            $result = [
                'users' => $max_users,
                'times' => [
                        date("H:i:s", strtotime($date_start))
                    ],
            ];
            foreach ($times as $time => $data){
                if ($time == '') continue; // Ключ null, так как нету даты разлогина. Не учитываем их, так как учтём логины
                if ($time > date("Y-m-d H:i:s", strtotime($date_end))) continue; // Не учитываем разлогины после нашего интервала времени
                foreach ($data as $user_id => $type){
                    if (!isset($online[$user_id])) $online[$user_id] = 0;
                    $online[$user_id] += $type; // Увеличиваем или уменьшаем количество сессий юзеру
                    if ($online[$user_id] == 0) unset($online[$user_id]);
                }
                if (count($online) > $max_users) {
                    $max_users = count($online);
                    $result = [
                        'users' => $max_users,
                        'times' => [date("H:i:s", strtotime($time))]
                    ];
                    $check = 1;
                }elseif ($check && count($online) < $max_users){
                    $result['times'][count($result['times']) - 1] .= ' - ' . date("H:i:s", strtotime($time));
                    $check = 0;
                }elseif(count($online) == $max_users && $check == 0){
                    $result['times'][count($result['times'])] = date("H:i:s", strtotime($time));
                    $check = 1;
                }
            }
        }else{
            return 0;
        }
        return view('main', ['result' => $result, 'dates' => date("d/m/Y", strtotime($date_start)) . ' - ' .date("d/m/Y", strtotime($date_end))]);
    }
}
