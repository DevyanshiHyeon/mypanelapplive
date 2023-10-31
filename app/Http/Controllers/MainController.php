<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('apps');
        } else {
            return view('welcome');
        }
    }
    public function dashboard()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        } else {
            return redirect('/')->with(['Error' => 'Login First']);
        }
    }
}
