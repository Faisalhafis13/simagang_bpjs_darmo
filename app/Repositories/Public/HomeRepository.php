<?php

namespace App\Repositories\Public;

class HomeRepository
{
    public function index()
    {
        return view('public.home.index');
    }
}