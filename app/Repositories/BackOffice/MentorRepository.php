<?php

namespace App\Repositories\BackOffice;

use App\Models\Mentor;
use Illuminate\Http\Request;

class MentorRepository
{
    public function index()
    {
        return view('back-office.mentor.index');
    }

    public function getData()
    {
        $mentors = Mentor::latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $mentors,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_mentor' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'tugas' => 'required|string',
        ]);

        Mentor::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor berhasil ditambahkan.',
        ]);
    }

    public function show($id)
    {
        $mentor = Mentor::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $mentor,
        ]);
    }

    public function update(Request $request, $id)
    {
        $mentor = Mentor::findOrFail($id);

        $data = $request->validate([
            'nama_mentor' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'tugas' => 'required|string',
        ]);

        $mentor->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        Mentor::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor berhasil dihapus.',
        ]);
    }
}
