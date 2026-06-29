<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Film;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource for the public frontend.
     */
    public function publicIndex(Request $request)
    {
        $query = Film::query();

        // Search logic
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%")
                  ->orWhere('sutradara', 'like', "%{$search}%");
            });
        }

        $films = $query->orderBy('judul', 'asc')->get();

        return view('frontend.index', compact('films'));
    }

    /**
     * Display a listing of the resource for the admin backend.
     */
    public function index()
    {
        $films = Film::orderBy('created_at', 'desc')->get();
        return view('backend.index', compact('films'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'genre' => ['required', 'string', 'max:255'],
            'sutradara' => ['required', 'string', 'max:255'],
            'tahun_rilis' => ['required', 'integer', 'min:1800', 'max:2100'],
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $path = $request->file('gambar')->store('films', 'public');

        Film::create([
            'judul' => $request->judul,
            'genre' => $request->genre,
            'sutradara' => $request->sutradara,
            'tahun_rilis' => $request->tahun_rilis,
            'gambar' => $path,
        ]);

        return redirect()->route('admin.index')
            ->with('success', 'Film baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $film = Film::findOrFail($id);
        return view('backend.edit', compact('film'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $film = Film::findOrFail($id);

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'genre' => ['required', 'string', 'max:255'],
            'sutradara' => ['required', 'string', 'max:255'],
            'tahun_rilis' => ['required', 'integer', 'min:1800', 'max:2100'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data = [
            'judul' => $request->judul,
            'genre' => $request->genre,
            'sutradara' => $request->sutradara,
            'tahun_rilis' => $request->tahun_rilis,
        ];

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($film->gambar && Storage::disk('public')->exists($film->gambar)) {
                Storage::disk('public')->delete($film->gambar);
            }
            // Upload new image
            $path = $request->file('gambar')->store('films', 'public');
            $data['gambar'] = $path;
        }

        $film->update($data);

        return redirect()->route('admin.index')
            ->with('success', 'Data film berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $film = Film::findOrFail($id);

        if ($film->gambar && Storage::disk('public')->exists($film->gambar)) {
            Storage::disk('public')->delete($film->gambar);
        }

        $film->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Film berhasil dihapus.');
    }

    /**
     * Export all movies list to PDF.
     */
    public function exportPdf()
    {
        $films = Film::orderBy('judul', 'asc')->get();
        $pdf = Pdf::loadView('reports.films_pdf', compact('films'));
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        return $pdf->download('laporan_film_bioskop.pdf');
    }
}
