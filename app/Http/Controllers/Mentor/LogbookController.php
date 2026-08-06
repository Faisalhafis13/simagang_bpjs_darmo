<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Mentor\LogbookRepository;


class LogbookController extends Controller
{

    protected $repository;


    public function __construct(LogbookRepository $repository )
    {
        $this->repository = $repository;
    }



    public function index()
    {
        return view('mentor.logbook.index');
    }



    public function peserta()
    {
        return response()->json(
            $this->repository->peserta()
        );
    }



    public function getData(Request $request)
    {
        return response()->json(
            $this->repository->getData($request)
        );
    }

}