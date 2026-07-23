<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Repositories\BackOffice\RoleUserRepository;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    protected RoleUserRepository $repository;

    public function __construct(RoleUserRepository $repository)
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

public function show($id)
{
    return $this->repository->show($id);
}

public function store(Request $request)
{
    $request->validate([

        'name'=>'required',

        'email'=>'required|email|unique:users,email',

        'password'=>'required',

        'role_id'=>'required'

    ]);

    return $this->repository->store($request);
}

public function update(Request $request,$id)
{
    $request->validate([

        'name'=>'required',

        'email'=>"required|email|unique:users,email,$id",

        'role_id'=>'required'

    ]);

    return $this->repository->update($request,$id);
}

public function destroy($id)
{
    return $this->repository->destroy($id);
}    public function edit($id)
{
    return response()->json(
        \App\Models\User::findOrFail($id)
    );
}
}