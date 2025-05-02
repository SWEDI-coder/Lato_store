<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Welcomecontroller extends Controller
{
    public function welcome()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $isDeveloper_Director = in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"]);

        if (!$user->role && !$isDeveloper_Director) {
            return redirect()->route('login');
        }
        return view('welcome');
    }
}
