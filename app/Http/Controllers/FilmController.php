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
     * Export movies list to PDF according to frontend pagination, search, and sorting.
     */
    public function exportPdf(Request $request)
    {
        $query = Film::query();

        // 1. Search logic (matching DataTables search on fields: id_film, judul, genre, sutradara, tahun_rilis)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                // Remove '#' prefix if searching by formatted ID (e.g. #0001)
                $cleanSearch = ltrim($search, '#');
                $cleanSearchInt = (int)$cleanSearch;
                
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%")
                  ->orWhere('sutradara', 'like', "%{$search}%")
                  ->orWhere('tahun_rilis', 'like', "%{$search}%");
                
                if (is_numeric($cleanSearch) && $cleanSearchInt > 0) {
                    $q->orWhere('id_film', $cleanSearchInt);
                }
            });
        }

        // 2. Sorting logic (matching DataTables order)
        $orderColIndex = $request->get('order_col');
        $orderDir = $request->get('order_dir', 'desc');
        if (!in_array(strtolower($orderDir), ['asc', 'desc'])) {
            $orderDir = 'desc';
        }

        // Column mapping based on index table columns:
        // index 0: Poster (not sorted)
        // index 1: ID Film (id_film)
        // index 2: Judul Film (judul)
        // index 3: Genre (genre)
        // index 4: Sutradara (sutradara)
        // index 5: Tahun (tahun_rilis)
        $columnsMap = [
            1 => 'id_film',
            2 => 'judul',
            3 => 'genre',
            4 => 'sutradara',
            5 => 'tahun_rilis',
        ];

        if ($orderColIndex !== null && array_key_exists((int)$orderColIndex, $columnsMap)) {
            $query->orderBy($columnsMap[(int)$orderColIndex], $orderDir);
        } else {
            // Default sort: order by ID descending (same as default DataTable config "order": [[1, "desc"]])
            $query->orderBy('id_film', 'desc');
        }

        // 3. Pagination/Limit logic
        $page = (int)$request->get('page', 1);
        $limit = (int)$request->get('limit', 10);

        if ($limit > 0) {
            $offset = ($page - 1) * $limit;
            $query->skip($offset)->take($limit);
        }

        $films = $query->get();

        $pdf = Pdf::loadView('reports.films_pdf', compact('films'));
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        return $pdf->download('laporan_film_bioskop.pdf');
    }
}
