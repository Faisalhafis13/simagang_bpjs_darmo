<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\HistoryRepository;

class HistoryController extends Controller
{
    protected HistoryRepository $repository;

    public function __construct(HistoryRepository $repository)
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
