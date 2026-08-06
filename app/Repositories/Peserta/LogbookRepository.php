<?php

namespace App\Repositories\Peserta;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookRepository
{
    public function index()
    {
        return view('peserta.logbook.index');
    }

    public function getData()
    {
        $data = Logbook::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'aktivitas' => 'required',
            'hasil'     => 'required',
            'catatan'   => 'nullable',
        ]);

        Logbook::create([
            'user_id'    => Auth::id(),
            'tanggal'    => $request->tanggal,
            'aktivitas'  => $request->aktivitas,
            'hasil'      => $request->hasil,
            'catatan'    => $request->catatan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil ditambahkan.'
        ]);
    }

    public function show($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $logbook
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'aktivitas' => 'required',
            'hasil'     => 'required',
            'catatan'   => 'nullable',
        ]);

        $logbook = Logbook::where('user_id', Auth::id())
            ->findOrFail($id);

        $logbook->update([
            'tanggal'   => $request->tanggal,
            'aktivitas' => $request->aktivitas,
            'hasil'     => $request->hasil,
            'catatan'   => $request->catatan,
        ]);

        return response()->json([
            'status'=>'success',
            'message'=>'Logbook berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->findOrFail($id);

        $logbook->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'Logbook berhasil dihapus.'
        ]);
    }
}