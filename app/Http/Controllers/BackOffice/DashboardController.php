<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('back-office.dashboard.index');
    }
}