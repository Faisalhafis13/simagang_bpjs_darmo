<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Repositories\Peserta\LogbookRepository;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    protected LogbookRepository $repository;

    public function __construct(LogbookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Halaman logbook peserta
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Data logbook milik peserta yang login
     */
    public function getData()
    {
        return $this->repository->getData();
    }

    /**
     * Simpan logbook baru
     */
    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Detail logbook
     */
    public function show($id)
    {
        return $this->repository->show($id);
    }

    /**
     * Update logbook
     */
    public function update(Request $request, $id)
    {
        return $this->repository->update($request, $id);
    }

    /**
     * Hapus logbook
     */
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}