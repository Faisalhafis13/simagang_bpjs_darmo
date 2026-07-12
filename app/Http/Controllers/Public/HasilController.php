<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Repositories\Public\HasilRepository;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    protected HasilRepository $repository;

    public function __construct(HasilRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function cari(Request $request)
    {
        return $this->repository->cari($request);
    }
}