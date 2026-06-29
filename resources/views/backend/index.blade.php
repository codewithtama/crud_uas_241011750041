@extends('layouts.app')

@section('title', 'Kelola Film - Admin')

@section('styles')
<!-- DataTables CSS for Tailwind -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Styling DataTables for Netflix dark mode compatibility */
    .dataTables_wrapper {
        color: #94a3b8 !important;
    }
    .dataTables_wrapper .dataTables_length select {
        background-color: #000000 !important;
        border-color: #2f2f2f !important;
        color: #ffffff !important;
        border-radius: 0.25rem !important;
        padding: 0.25rem 1.5rem 0.25rem 0.5rem !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        background-color: #000000 !important;
        border-color: #2f2f2f !important;
        color: #ffffff !important;
        border-radius: 0.25rem !important;
        padding: 0.375rem 0.75rem !important;
    }
    table.dataTable {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        width: 100% !important;
        margin: 1.5rem 0 !important;
        background: transparent !important;
    }
    table.dataTable thead th {
        background-color: #000000 !important;
        color: #ffffff !important;
        border-bottom: 2px solid #E50914 !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em !important;
        padding: 14px 16px !important;
    }
    table.dataTable tbody tr {
        background-color: transparent !important;
        border-bottom: 1px solid #1f1f1f !important;
    }
    table.dataTable tbody tr:hover {
        background-color: rgba(229, 9, 20, 0.05) !important;
    }
    table.dataTable tbody td {
        padding: 14px 16px !important;
        color: #cbd5e1 !important;
        font-size: 0.875rem !important;
    }
    /* Pagination styling overrides to match Netflix Red */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #94a3b8 !important;
        background: transparent !important;
        border: 1px solid #2f2f2f !important;
        border-radius: 0.25rem !important;
        margin: 0 2px !important;
        padding: 6px 12px !important;
        font-size: 0.75rem !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #E50914 !important;
        color: #ffffff !important;
        border-color: #E50914 !important;
        font-weight: 600 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #2f2f2f !important;
        color: #ffffff !important;
        border-color: #5a5a5a !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #5a5a5a !important;
        border-color: #1f1f1f !important;
        background: transparent !important;
        cursor: not-allowed !important;
    }
    .dataTables_wrapper .dataTables_info {
        color: #5a5a5a !important;
        font-size: 0.75rem !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 space-y-8">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-6 bg-zinc-900 border border-zinc-800 rounded-lg">
        <div>
            <h2 class="text-2xl font-bold font-bebas tracking-wide text-white flex items-center gap-2.5">
                <i class="fa-solid fa-list-check text-netflix-red"></i> Dashboard Kelola Film Bioskop
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                Panel administrator utama untuk menambah, memperbarui, menghapus data katalog film, serta mengunduh laporan PDF.
            </p>
        </div>
        
        <div class="flex flex-wrap gap-2.5">
            <a id="btnExportPdf" href="{{ route('admin.export-pdf') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-slate-300 bg-[#141414] border border-zinc-800 hover:bg-zinc-900 rounded transition-colors cursor-pointer">
                <i class="fa-solid fa-file-pdf text-netflix-red mr-2"></i> Export PDF
            </a>
            <a href="{{ route('admin.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-netflix-red hover:bg-[#b81d24] rounded transition-all shadow-md shadow-netflix-red/10 cursor-pointer">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Film Baru
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="p-6 bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table id="filmsTable" class="display min-w-full">
                <thead>
                    <tr>
                        <th class="w-16">Poster</th>
                        <th class="w-20">ID Film</th>
                        <th>Judul Film</th>
                        <th>Genre</th>
                        <th>Sutradara</th>
                        <th class="w-20">Tahun</th>
                        <th class="w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($films as $film)
                        <tr>
                            <!-- Poster -->
                            <td>
                                <div class="w-12 aspect-[2/3] rounded overflow-hidden border border-zinc-800 bg-black">
                                    @if($film->gambar)
                                        <img src="{{ (strpos($film->gambar, 'http') === 0) ? $film->gambar : asset('storage/' . $film->gambar) }}" alt="Poster" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-zinc-800 text-zinc-600">
                                            <i class="fa-solid fa-image text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <!-- ID Film -->
                            <td class="font-mono text-xs text-slate-400">
                                #{{ str_pad($film->id_film, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <!-- Judul -->
                            <td class="font-bold text-white">
                                {{ $film->judul }}
                            </td>
                            <!-- Genre -->
                            <td>
                                <span class="text-xs text-slate-300 font-semibold text-netflix-red">{{ $film->genre }}</span>
                            </td>
                            <!-- Sutradara -->
                            <td>
                                <span class="text-xs text-slate-400">{{ $film->sutradara }}</span>
                            </td>
                            <!-- Tahun Rilis -->
                            <td class="font-semibold text-white">
                                {{ $film->tahun_rilis }}
                            </td>
                            <!-- Action buttons -->
                            <td>
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.edit', $film->id_film) }}" class="inline-flex items-center justify-center w-8 h-8 rounded bg-zinc-800 border border-zinc-700 text-slate-300 hover:text-white hover:bg-netflix-red hover:border-netflix-red transition-all cursor-pointer" title="Ubah Film">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.destroy', $film->id_film) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus film ini? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded bg-zinc-800 border border-zinc-700 text-slate-300 hover:text-white hover:bg-netflix-red hover:border-netflix-red transition-all cursor-pointer" title="Hapus Film">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery and DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#filmsTable').DataTable({
            "order": [[1, "desc"]], // Order by ID descending by default
            "pageLength": 10,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data film ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "<i class='fa-solid fa-chevron-right'></i>",
                    "previous": "<i class='fa-solid fa-chevron-left'></i>"
                }
            }
        });

        // Update PDF export link dynamically on table draw
        table.on('draw', function() {
            var info = table.page.info();
            var page = info.page + 1;
            var limit = info.length;
            var search = table.search();
            var order = table.order();

            var params = $.param({
                page: page,
                limit: limit,
                search: search,
                order_col: order[0] ? order[0][0] : '',
                order_dir: order[0] ? order[0][1] : ''
            });

            var baseUrl = "{{ route('admin.export-pdf') }}";
            $('#btnExportPdf').attr('href', baseUrl + '?' + params);
        });

        // Trigger initial update of PDF link
        table.trigger('draw');
    });
</script>
@endsection

