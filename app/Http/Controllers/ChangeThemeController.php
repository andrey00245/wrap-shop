<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChangeThemeController extends Controller
{
    public function __invoke(Request $request)
    {
        if(Session::get('theme') === 'dark'){
            Session::put('theme', 'light');
        } else {
            Session::put('theme', 'dark');
        }

        return redirect()->back();
    }
}
