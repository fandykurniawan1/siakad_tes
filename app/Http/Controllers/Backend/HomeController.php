<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('backend.dashboard');
    }

    public function dashboard()
    {
        return view('backend.dashboard');
    }
}
