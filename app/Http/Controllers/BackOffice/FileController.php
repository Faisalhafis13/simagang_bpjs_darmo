<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Serve private files stored under storage/app/private/{type}/{filename}
     */
    public function preview($type, $filename)
    {
        $safeName = basename($filename);

        $path = storage_path('app/private/' . $type . '/' . $safeName);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

}
