<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\PengajuanRepository;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    protected PengajuanRepository $repository;

    public function __construct(PengajuanRepository $repository)
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

    public function update(Request $request, $id)
    {
        return $this->repository->update($request, $id);
    }

    public function archive($id)
    {
        return $this->repository->archive($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Preview Dokumen Pengajuan Aktif
    |--------------------------------------------------------------------------
    */

    public function file($id, $type)
    {
        return $this->repository->file($id, $type);
    }
}