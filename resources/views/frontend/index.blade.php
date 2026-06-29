@extends('layouts.app')

@section('title', 'Katalog Film')

@section('styles')
<style>
    /* Hero animations */
    .billboard-container {
        position: relative;
        height: 70vh;
        width: 100%;
        overflow: hidden;
    }
    @media (min-width: 640px) {
        .billboard-container {
            height: 85vh;
        }
    }
    
    /* Hover scale and preview panel details */
    .movie-row-card {
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
    }
    .movie-row-card:hover {
        transform: scale(1.08);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.8);
        z-index: 30;
    }
    
    /* Fade animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in-modal {
        animation: fadeIn 0.4s ease forwards;
    }
</style>
@endsection

@section('content')
<div class="space-y-16 -mt-16 sm:-mt-20">
    
    @php
        $featured = $films->first();
    @endphp

    <!-- Billboard / Hero Section -->
    <div class="billboard-container bg-netflix-black">
        @if($featured)
            <!-- Background Image -->
            <div class="absolute inset-0 w-full h-full">
                @if($featured->gambar)
                    <img src="{{ (strpos($featured->gambar, 'http') === 0) ? $featured->gambar : asset('storage/' . $featured->gambar) }}" alt="Featured Poster" class="w-full h-full object-cover object-top filter brightness-[0.7] contrast-[1.05]">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-950 flex items-center justify-center text-zinc-700">
                        <i class="fa-solid fa-film text-9xl"></i>
                    </div>
                @endif
                <!-- Netflix gradients -->
                <div class="absolute inset-0 netflix-gradient-left"></div>
                <div class="absolute inset-0 netflix-gradient-bottom"></div>
            </div>

            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-end pb-12 sm:pb-20 px-4 sm:px-12 md:px-16 max-w-4xl space-y-4 z-10">
                <!-- N-Series tag -->
                <div class="flex items-center gap-2">
                    <span class="text-netflix-red font-bebas text-3xl tracking-widest animate-pulse">N</span>
                    <span class="text-xs uppercase tracking-widest text-slate-300 font-bold">FILM POPULER</span>
                </div>
                
                <!-- Title -->
                <h1 class="text-4xl sm:text-6xl md:text-7xl font-bebas tracking-wide text-white drop-shadow-xl uppercase line-clamp-2">
                    {{ $featured->judul }}
                </h1>

                <!-- Movie Info Row -->
                <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm font-medium text-slate-300">
                    <span class="text-green-500 font-bold">98% Cocok</span>
                    <span class="text-white bg-zinc-800 px-2 py-0.5 rounded text-xs border border-zinc-700">{{ $featured->tahun_rilis }}</span>
                    <span class="border border-slate-500 px-1 text-xs rounded text-slate-400">HD</span>
                    <span class="text-netflix-red font-semibold">{{ $featured->genre }}</span>
                </div>

                <!-- Synopsis Fallback -->
                <p class="text-sm sm:text-base text-slate-300 max-w-xl drop-shadow-md leading-relaxed line-clamp-3">
                    Saksikan petualangan sinematik yang mendebarkan besutan sutradara ternama <strong class="text-white">{{ $featured->sutradara }}</strong>. Kisah legendaris bergenre <strong class="text-white">{{ $featured->genre }}</strong> ini akan membawa Anda melintasi batas imajinasi dan perjuangan emosional yang mendalam.
                </p>

                <!-- Actions -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button onclick="playTrailer({{ json_encode($featured) }})" class="inline-flex items-center justify-center px-6 py-2.5 sm:px-8 sm:py-3 bg-white text-black font-bold rounded hover:bg-slate-200 transition-colors shadow-lg cursor-pointer text-sm sm:text-base">
                        <i class="fa-solid fa-play mr-2.5 text-base sm:text-lg"></i> Putar Trailer
                    </button>
                    <button onclick="showMovieDetail({{ json_encode($featured) }})" class="inline-flex items-center justify-center px-6 py-2.5 sm:px-8 sm:py-3 bg-[#5a5a5a]/60 text-white font-bold rounded hover:bg-[#5a5a5a]/40 backdrop-blur-md transition-colors shadow-lg cursor-pointer text-sm sm:text-base">
                        <i class="fa-solid fa-circle-info mr-2.5 text-base sm:text-lg"></i> Selengkapnya
                    </button>
                </div>
            </div>
        @else
            <!-- Fallback Hero in case database is empty -->
            <div class="absolute inset-0 w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-zinc-900 via-netflix-black to-black flex flex-col items-center justify-center text-center p-6 space-y-6">
                <h1 class="text-5xl sm:text-7xl font-bebas text-netflix-red tracking-wider">CineManage Bioskop</h1>
                <p class="text-slate-400 text-sm sm:text-lg max-w-xl">
                    Selamat datang di portal film kami. Belum ada film yang terdaftar. Masuk ke halaman admin untuk mengelola katalog data film bioskop Anda.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-netflix-red text-white font-bold rounded hover:bg-[#b81d24] transition-all shadow-lg shadow-netflix-red/30">
                        <i class="fa-solid fa-user-shield mr-2"></i> Login Admin
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Search Bar Section -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <form action="{{ route('home') }}" method="GET" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-netflix-red transition-colors">
                <i class="fa-solid fa-magnifying-glass text-lg"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul film, genre, atau sutradara..." 
                class="block w-full pl-12 pr-44 py-3.5 bg-zinc-900/80 border border-zinc-800 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-netflix-red/50 focus:border-netflix-red transition-all text-sm backdrop-blur-md">
            
            <div class="absolute inset-y-1 right-1.5 flex items-center gap-2">
                @if(request('search'))
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-slate-300 rounded-md text-sm font-semibold flex items-center transition-all active:scale-95">
                        Clear
                    </a>
                @endif
                <button type="submit" class="px-6 py-2 bg-netflix-red hover:bg-[#b81d24] text-white rounded-md text-sm font-bold transition-all active:scale-95 cursor-pointer shadow-md hover:shadow-lg">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Catalog Grid or Genre Rows -->
    <div class="px-4 sm:px-12 md:px-16 space-y-12">
        @if($films->isEmpty())
            <div class="text-center py-20 bg-zinc-950/40 rounded-xl border border-zinc-900 flex flex-col items-center justify-center space-y-4">
                <div class="w-20 h-20 bg-zinc-900 rounded-full flex items-center justify-center border border-zinc-800 shadow-inner">
                    <i class="fa-solid fa-film-slash text-slate-600 text-3xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-bold text-white">Film Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500">Tidak ada film yang cocok dengan kata kunci pencarian Anda.</p>
                </div>
                @if(request('search'))
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded text-xs font-semibold transition-colors">
                        Kembali ke Katalog
                    </a>
                @endif
            </div>
        @else
            @if(request('search'))
                <!-- Plain Grid view for Search results -->
                <div class="space-y-6">
                    <h3 class="text-lg sm:text-xl font-medium text-slate-400">
                        Hasil pencarian untuk: <span class="text-white font-bold">"{{ request('search') }}"</span>
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach($films as $film)
                            <div onclick="showMovieDetail({{ json_encode($film) }})" class="movie-row-card group flex flex-col bg-netflix-gray border border-zinc-900 rounded overflow-hidden cursor-pointer">
                                <div class="aspect-[2/3] w-full relative">
                                    @if($film->gambar)
                                        <img src="{{ (strpos($film->gambar, 'http') === 0) ? $film->gambar : asset('storage/' . $film->gambar) }}" alt="{{ $film->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-zinc-900 text-zinc-700">
                                            <i class="fa-solid fa-film text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 space-y-1 bg-zinc-950">
                                    <h4 class="text-sm font-bold text-white truncate">{{ $film->judul }}</h4>
                                    <p class="text-[10px] text-netflix-red font-semibold">{{ $film->genre }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Netflix Style Genre Lanes -->
                @php
                    $filmsByGenre = $films->groupBy('genre');
                @endphp

                <!-- Trending / All Films Row -->
                <div class="space-y-4">
                    <h2 class="text-lg sm:text-2xl font-bold text-white tracking-wide flex items-center gap-2">
                        Sedang Populer Saat Ini
                        <span class="w-1.5 h-1.5 bg-netflix-red rounded-full animate-ping"></span>
                    </h2>
                    <div class="relative">
                        <div class="flex gap-4 overflow-x-auto no-scrollbar py-4 scroll-smooth">
                            @foreach($films as $film)
                                <div onclick="showMovieDetail({{ json_encode($film) }})" class="movie-row-card flex-shrink-0 w-36 sm:w-48 bg-netflix-gray border border-zinc-900 rounded overflow-hidden cursor-pointer shadow-lg shadow-black/40">
                                    <div class="aspect-[2/3] w-full relative bg-zinc-950">
                                        @if($film->gambar)
                                            <img src="{{ (strpos($film->gambar, 'http') === 0) ? $film->gambar : asset('storage/' . $film->gambar) }}" alt="{{ $film->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-zinc-900 text-zinc-700">
                                                <i class="fa-solid fa-film text-2xl"></i>
                                            </div>
                                        @endif
                                        <div class="absolute bottom-2 left-2 px-1.5 py-0.5 bg-black/80 rounded border border-zinc-700 text-[9px] text-slate-300">
                                            {{ $film->tahun_rilis }}
                                        </div>
                                    </div>
                                    <div class="p-3 bg-zinc-950/90 border-t border-zinc-900 space-y-1">
                                        <h4 class="text-xs sm:text-sm font-bold text-white truncate">{{ $film->judul }}</h4>
                                        <div class="flex items-center justify-between text-[9px] text-slate-400">
                                            <span class="text-green-500 font-semibold">98% Match</span>
                                            <span class="truncate max-w-[65px]">{{ $film->genre }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Dynanmic Genre Lanes -->
                @foreach($filmsByGenre as $genreName => $genreFilms)
                    <div class="space-y-4">
                        <h2 class="text-lg sm:text-2xl font-bold text-white tracking-wide hover:text-netflix-red transition-colors cursor-pointer inline-flex items-center gap-1">
                            {{ $genreName }} <i class="fa-solid fa-chevron-right text-xs text-slate-500 ml-1"></i>
                        </h2>
                        <div class="relative">
                            <div class="flex gap-4 overflow-x-auto no-scrollbar py-4 scroll-smooth">
                                @foreach($genreFilms as $film)
                                    <div onclick="showMovieDetail({{ json_encode($film) }})" class="movie-row-card flex-shrink-0 w-36 sm:w-48 bg-netflix-gray border border-zinc-900 rounded overflow-hidden cursor-pointer shadow-lg shadow-black/40">
                                        <div class="aspect-[2/3] w-full relative bg-zinc-950">
                                            @if($film->gambar)
                                                <img src="{{ (strpos($film->gambar, 'http') === 0) ? $film->gambar : asset('storage/' . $film->gambar) }}" alt="{{ $film->judul }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-zinc-900 text-zinc-700">
                                                    <i class="fa-solid fa-film text-2xl"></i>
                                                </div>
                                            @endif
                                            <div class="absolute bottom-2 left-2 px-1.5 py-0.5 bg-black/80 rounded border border-zinc-700 text-[9px] text-slate-300">
                                                {{ $film->tahun_rilis }}
                                            </div>
                                        </div>
                                        <div class="p-3 bg-zinc-950/90 border-t border-zinc-900 space-y-1">
                                            <h4 class="text-xs sm:text-sm font-bold text-white truncate">{{ $film->judul }}</h4>
                                            <div class="flex items-center justify-between text-[9px] text-slate-400">
                                                <span class="text-green-500 font-semibold">98% Match</span>
                                                <span class="truncate max-w-[65px] text-netflix-red font-semibold">{{ $film->sutradara }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
</div>

<!-- Netflix Movie Detail Modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-sm animate-fade-in-modal">
    <div class="relative w-full max-w-2xl bg-netflix-gray rounded-lg overflow-hidden border border-zinc-800 shadow-2xl my-8">
        <!-- Close button -->
        <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/70 hover:bg-black text-white flex items-center justify-center transition-colors cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        
        <!-- Modal Billboard Cover -->
        <div class="relative aspect-video w-full">
            <img id="modalCover" src="" alt="Cover" class="w-full h-full object-cover object-top filter brightness-[0.7]">
            <div class="absolute inset-0 netflix-gradient-bottom"></div>
            
            <div class="absolute bottom-6 left-6 right-6 flex items-end justify-between">
                <div class="space-y-2">
                    <h3 id="modalTitle" class="text-2xl sm:text-4xl font-bebas text-white tracking-wide"></h3>
                    <div class="flex items-center gap-2.5 text-xs text-slate-300">
                        <span class="text-green-500 font-bold">98% Cocok</span>
                        <span id="modalYear" class="border border-zinc-700 bg-zinc-800 px-1.5 py-0.5 rounded"></span>
                        <span class="border border-slate-500 px-1 rounded">HD</span>
                        <span id="modalGenre" class="text-netflix-red font-bold"></span>
                    </div>
                </div>
                <button id="modalPlayBtn" class="px-6 py-2 bg-white text-black font-bold rounded hover:bg-slate-200 transition-colors flex items-center gap-2 cursor-pointer shadow-lg text-sm">
                    <i class="fa-solid fa-play text-xs"></i> Play
                </button>
            </div>
        </div>
        
        <!-- Modal Content Details -->
        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm text-slate-300 bg-zinc-950">
            <div class="sm:col-span-2 space-y-4">
                <p id="modalSynopsis" class="text-slate-300 leading-relaxed"></p>
            </div>
            
            <div class="space-y-3 pt-1 border-t sm:border-t-0 sm:border-l border-zinc-800 sm:pl-6 text-xs text-slate-400">
                <div>
                    <span class="block text-[10px] uppercase text-slate-500 tracking-wider">Sutradara</span>
                    <span id="modalDirector" class="text-white font-medium text-sm"></span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase text-slate-500 tracking-wider">Kategori / Genre</span>
                    <span id="modalGenreLabel" class="text-white font-medium text-sm"></span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase text-slate-500 tracking-wider">Kode Identitas</span>
                    <span id="modalIdCode" class="text-zinc-400 font-mono text-sm"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Netflix Play Trailer Simulation Modal -->
<div id="playModal" class="fixed inset-0 z-50 hidden bg-black flex items-center justify-center p-0">
    <div class="relative w-full h-full flex flex-col justify-between">
        
        <!-- Top bar overlay (back button) -->
        <div class="absolute top-0 inset-x-0 p-4 bg-gradient-to-b from-black/80 to-transparent flex items-center gap-4 z-10">
            <button onclick="closeModal('playModal')" class="w-10 h-10 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center transition-colors cursor-pointer">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </button>
            <div>
                <span class="text-xs uppercase text-netflix-red tracking-widest font-bold">Memutar Trailer</span>
                <h4 id="playModalTitle" class="text-lg font-bebas text-white tracking-wide"></h4>
            </div>
        </div>

        <!-- Simulated Video screen -->
        <div class="flex-grow flex items-center justify-center bg-black relative overflow-hidden">
            <!-- Simulated Loading Indicator -->
            <div id="playerLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-black z-20 space-y-4">
                <div class="w-14 h-14 border-4 border-t-netflix-red border-zinc-800 rounded-full animate-spin"></div>
                <span class="text-xs text-slate-400 tracking-widest font-mono">BUFFERS LOADING...</span>
            </div>
            
            <!-- A nice dynamic backdrop / simulated trailer using HTML5 canvas or video placeholder with audio/visuals -->
            <div id="playerScreen" class="w-full h-full flex flex-col items-center justify-center text-center p-8 space-y-4 bg-gradient-to-tr from-zinc-950 via-zinc-900 to-zinc-950 hidden">
                <i class="fa-solid fa-circle-play text-8xl text-netflix-red animate-pulse"></i>
                <div class="space-y-1">
                    <h3 class="text-2xl font-bebas text-white tracking-widest">VIDEO PLAYBACK SIMULATOR</h3>
                    <p class="text-slate-500 text-xs max-w-md mx-auto">
                        Memutar konten promo resolusi tinggi untuk film bioskop ini. Fitur streaming berjalan lancar pada infrastruktur portal cloud server.
                    </p>
                </div>
            </div>
        </div>

        <!-- Simulated Player Controls -->
        <div class="p-6 bg-gradient-to-t from-black to-transparent flex items-center justify-between text-white z-10">
            <div class="flex items-center gap-6">
                <button onclick="togglePlaySim()" class="text-lg hover:text-netflix-red transition-colors"><i id="playSimIcon" class="fa-solid fa-pause"></i></button>
                <button class="text-lg hover:text-slate-300 transition-colors"><i class="fa-solid fa-rotate-left"></i></button>
                <button class="text-lg hover:text-slate-300 transition-colors"><i class="fa-solid fa-rotate-right"></i></button>
                <button class="text-lg hover:text-slate-300 transition-colors"><i class="fa-solid fa-volume-high"></i></button>
            </div>
            <div class="flex-grow max-w-xl mx-8 relative">
                <div class="h-1 bg-zinc-700 rounded-full w-full overflow-hidden">
                    <div id="playProgressBar" class="h-full bg-netflix-red w-[18%] transition-all duration-1000"></div>
                </div>
            </div>
            <div class="flex items-center gap-6 text-sm text-slate-400">
                <span id="playTimer">0:18 / 2:30</span>
                <button class="text-lg hover:text-white transition-colors"><i class="fa-solid fa-expand"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show Movie details modal
    function showMovieDetail(film) {
        // Construct asset storage path
        const imagePath = film.gambar ? (film.gambar.startsWith('http') ? film.gambar : `{{ asset('storage') }}/${film.gambar}`) : '';
        
        document.getElementById('modalCover').src = imagePath;
        document.getElementById('modalTitle').textContent = film.judul;
        document.getElementById('modalYear').textContent = film.tahun_rilis;
        document.getElementById('modalGenre').textContent = film.genre;
        document.getElementById('modalGenreLabel').textContent = film.genre;
        document.getElementById('modalDirector').textContent = film.sutradara;
        document.getElementById('modalIdCode').textContent = `#${String(film.id_film).padStart(4, '0')}`;
        
        // Mock cinematic synopsis
        document.getElementById('modalSynopsis').innerHTML = `
            Saksikan mahakarya sutradara ternama <strong>${film.sutradara}</strong> dalam film bergenre <strong>${film.genre}</strong>. 
            Menceritakan petualangan luar biasa yang diangkat dari kisah epik penuh emosi, perjuangan hidup, dan intrik mendebarkan. 
            Film rilisan tahun <strong>${film.tahun_rilis}</strong> ini menawarkan visual efek berkelas dunia dan aransemen musik orkestra yang megah, 
            menjadikannya salah satu tontonan bioskop terbaik yang wajib Anda saksikan.
        `;
        
        // Play button handler in modal
        document.getElementById('modalPlayBtn').onclick = function() {
            closeModal('detailModal');
            playTrailer(film);
        };
        
        document.getElementById('detailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Disable scroll background
    }

    // Play Trailer simulation
    let playTimerInterval;
    let simSeconds = 18;
    let isSimPlaying = true;

    function playTrailer(film) {
        document.getElementById('playModalTitle').textContent = film.judul;
        document.getElementById('playModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Show loading then show player screen after 1.5s
        document.getElementById('playerLoading').classList.remove('hidden');
        document.getElementById('playerScreen').classList.add('hidden');
        
        simSeconds = 18;
        isSimPlaying = true;
        document.getElementById('playSimIcon').className = "fa-solid fa-pause";
        updateSimProgress();

        setTimeout(() => {
            document.getElementById('playerLoading').classList.add('hidden');
            document.getElementById('playerScreen').classList.remove('hidden');
            startSimPlayback();
        }, 1500);
    }

    function startSimPlayback() {
        clearInterval(playTimerInterval);
        playTimerInterval = setInterval(() => {
            if (isSimPlaying) {
                simSeconds++;
                if (simSeconds >= 150) {
                    simSeconds = 0;
                }
                updateSimProgress();
            }
        }, 1000);
    }

    function updateSimProgress() {
        const total = 150; // 2:30
        const pct = (simSeconds / total) * 100;
        document.getElementById('playProgressBar').style.width = pct + '%';
        
        const curMin = Math.floor(simSeconds / 60);
        const curSec = simSeconds % 60;
        const padSec = String(curSec).padStart(2, '0');
        document.getElementById('playTimer').textContent = `${curMin}:${padSec} / 2:30`;
    }

    function togglePlaySim() {
        isSimPlaying = !isSimPlaying;
        document.getElementById('playSimIcon').className = isSimPlaying ? "fa-solid fa-pause" : "fa-solid fa-play";
    }

    // Close Modals
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = ''; // Re-enable background scrolling
        
        if (modalId === 'playModal') {
            clearInterval(playTimerInterval);
        }
    }
    
    // Close modal if user clicks outside of modal card
    window.onclick = function(event) {
        const detailModal = document.getElementById('detailModal');
        if (event.target === detailModal) {
            closeModal('detailModal');
        }
    }
</script>
@endsection

