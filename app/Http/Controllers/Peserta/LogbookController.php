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
    public function index()
    {
        return $this->repository->index();
    }
    public function getData()
    {
        return $this->repository->getData();
    }
    public function store(Request $request)
    {
        return $this->repository->store($request);
    }
    public function show($id)
    {
        return $this->repository->show($id);
    }
    public function update(Request $request, $id)
    {
        return $this->repository->update($request, $id);
    }
    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}