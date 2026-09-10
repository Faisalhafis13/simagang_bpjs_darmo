<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\PesertaRepository;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    protected PesertaRepository $repository;

    public function __construct(PesertaRepository $repository)
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
    public function uploadSuratPenerimaan(Request $request, $id)
    {
        return $this->repository->uploadSuratPenerimaan(
            $request,
            $id
        );
    }
    public function deleteSuratPenerimaan($id)
    {
        return $this->repository->deleteSuratPenerimaan($id);
    }
}