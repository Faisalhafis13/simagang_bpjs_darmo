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

    /**
     * Halaman arsip pengajuan.
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Data DataTable.
     */
    public function getData()
    {
        return $this->repository->getData();
    }

    /**
     * Detail arsip pengajuan.
     */
    public function detail($id)
    {
        return $this->repository->detail($id);
    }

    /**
     * Preview / lihat dokumen arsip.
     */
    public function file($id, $type)
    {
        return $this->repository->file($id, $type);
    }
}