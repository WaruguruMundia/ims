<?php

namespace App\Http\Controllers;

class SupervisorDashboardController extends Controller
{
    public function index(){
        return view('supervisor.dashboard');
    }
}
