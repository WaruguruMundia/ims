<?php

namespace App\Http\Controllers;

class InternDashboardController extends Controller
{
    public function index(){
        return view('intern.dashboard');
    }
}
