<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Repositories\Public\PengajuanRepository;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    protected PengajuanRepository $repository;

    public function __construct(PengajuanRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Menampilkan halaman pengajuan
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Menyimpan data pengajuan
     */
    public function store(Request $request)
    {
        return $this->repository->store($request);
    }
}