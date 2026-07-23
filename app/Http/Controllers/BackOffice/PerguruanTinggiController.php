<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\PerguruanTinggiRepository;
use Illuminate\Http\Request;

class PerguruanTinggiController extends Controller
{
    protected PerguruanTinggiRepository $repository;

    public function __construct(PerguruanTinggiRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function getData()
    {
        return $this->repository->getData();
    }
}
