<?php

namespace App\Repositories\Mentor;

use App\Models\Logbook;
use Illuminate\Support\Facades\Auth;

class LogbookRepository
{
public function peserta()
{
    $user = Auth::user();


    $mentor = \App\Models\Mentor::where(
        'nama_mentor',
        $user->name
    )->first();



    if (!$mentor) {
        return collect([]);
    }



    return $mentor->peserta()
        ->select(
            'id',
            'name'
        )
        ->orderBy(
            'name',
            'asc'
        )
        ->get();
}
public function getData($request)
{
    $user = Auth::user();


    $mentor = \App\Models\Mentor::where(
        'nama_mentor',
        $user->name
    )->first();



    if (!$mentor) {
        return collect([]);
    }



    return Logbook::whereHas(
        'user',
        function($query) use ($mentor){

            $query->where(
                'mentor_id',
                $mentor->id
            );

        }
    )
    ->where(
        'user_id',
        $request->user_id
    )
    ->orderBy(
        'tanggal',
        'desc'
    )
    ->get();
}

}