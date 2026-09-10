<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\ArsipPengajuanRepository;

class ArsipPengajuanController extends Controller
{
    protected ArsipPengajuanRepository $repository;

    public function __construct(
        ArsipPengajuanRepository $repository
    ) {
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
    public function detail($id)
    {
        return $this->repository->detail($id);
    }
    public function file($id, $type)
    {
        return $this->repository->file($id, $type);
    }
}