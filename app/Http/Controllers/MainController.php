<?php

namespace App\Http\Controllers;

use App\Session;

class MainController extends Controller
{
    public function index()
    {
        $date = $_GET['date'];

        $items = Session::where('login_time', '>=', $date)
            ->sortBy('id')->toArray();

        $items = [];
        return view('main', ['items' => $items]);
    }
}
