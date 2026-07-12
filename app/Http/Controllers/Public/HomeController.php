<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Repositories\Public\HomeRepository;

class HomeController extends Controller
{
    protected HomeRepository $repository;

    public function __construct(HomeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }
}