<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Welcomecontroller extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }
}
