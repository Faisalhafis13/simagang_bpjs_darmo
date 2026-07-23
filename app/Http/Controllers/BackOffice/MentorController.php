<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\MentorRepository;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    protected MentorRepository $repository;

    public function __construct(MentorRepository $repository)
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

    public function update(Request $request, $id)
    {
        return $this->repository->update($request, $id);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
