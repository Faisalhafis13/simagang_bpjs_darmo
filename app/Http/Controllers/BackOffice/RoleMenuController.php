<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Repositories\BackOffice\RoleMenuRepository;
use Illuminate\Http\Request;

class RoleMenuController extends Controller
{
    protected RoleMenuRepository $repository;

    public function __construct(RoleMenuRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function getData(Request $request)
    {
        return $this->repository->getData($request);
    }

    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    public function update(Request $request, RoleMenu $roleMenu)
    {
        return $this->repository->update($request, $roleMenu);
    }

    public function destroy(RoleMenu $roleMenu)
    {
        return $this->repository->destroy($roleMenu);
    }
    public function show(RoleMenu $roleMenu)
{
    return response()->json([
        'status' => 'success',
        'data' => $roleMenu
    ]);
}
}
