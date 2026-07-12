<?php

namespace App\Repositories\Public;

use Illuminate\Http\Request;

class LoginRepository
{
    public function login(Request $request)
    {
        return response()->json([
            'success' => true
        ]);
    }
}