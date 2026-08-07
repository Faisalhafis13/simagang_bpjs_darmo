<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Repositories\Peserta\PesertaRepository;

class PesertaController extends Controller
{
    protected PesertaRepository $repository;

    public function __construct(
        PesertaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }
}