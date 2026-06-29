<!DOCTYPE html>
<html lang="id" class="h-full bg-[#141414] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer">
    <meta name="description" content="Aplikasi CRUD Data Film Bioskop untuk memenuhi Tugas UAS Rekayasa Web.">
    <title>@yield('title', 'CineManage') - Portal Data Film Bioskop</title>
    
    <!-- Google Fonts: Bebas Neue (Netflix style titles) & Plus Jakarta Sans (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        bebas: ['"Bebas Neue"', 'sans-serif'],
                    },
                    colors: {
                        netflix: {
                            red: '#E50914',
                            dark: '#141414',
                            black: '#000000',
                            gray: '#181818',
                            'light-gray': '#2f2f2f',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Netflix custom layout styles -->
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        /* Custom scrollbar to match Netflix dark aesthetic */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #141414;
        }
        ::-webkit-scrollbar-thumb {
            background: #2f2f2f;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #E50914;
        }
        
        /* Netflix fade gradient for billboards */
        .netflix-gradient-bottom {
            background: linear-gradient(to top, #141414 0%, rgba(20, 20, 20, 0.8) 20%, rgba(20, 20, 20, 0.5) 45%, rgba(20, 20, 20, 0.2) 70%, transparent 100%);
        }
        
        .netflix-gradient-left {
            background: linear-gradient(to right, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 30%, rgba(0, 0, 0, 0.3) 60%, transparent 100%);
        }

        /* Glassmorphism/Dark Card */
        .netflix-card {
            background: #181818;
            border: 1px solid #2f2f2f;
        }

        /* Hide scrollbar for slider rows */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    @yield('styles')
</head>
<body class="font-sans flex flex-col min-h-screen bg-[#141414] text-slate-100 selection:bg-netflix-red selection:text-white">

    <!-- Header / Navbar -->
    <header id="mainHeader" class="fixed top-0 z-50 w-full transition-all duration-500 bg-gradient-to-b from-black/80 to-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center text-3xl sm:text-4xl font-bebas tracking-wider text-netflix-red hover:scale-105 transition-transform duration-300">
                        <span>CINE<span class="text-white">MANAGE</span></span>
                    </a>
                </div>

                <!-- Right Side Header Elements -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Search Bar (Desktop) -->
                    <form action="{{ route('home') }}" method="GET" class="relative hidden md:flex items-center">
                        <button type="submit" class="absolute left-3 text-slate-400 hover:text-white transition-colors cursor-pointer focus:outline-none">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </button>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul film, genre, atau sutradara..." 
                            class="w-64 lg:w-96 pl-9 pr-8 py-2 bg-zinc-900/60 border border-zinc-700/60 rounded-md text-sm text-white placeholder-slate-500 focus:outline-none focus:w-80 lg:focus:w-[480px] focus:bg-black focus:border-netflix-red focus:ring-1 focus:ring-netflix-red transition-all duration-300">
                        @if(request('search'))
                            <a href="{{ route('home') }}" class="absolute right-2.5 text-slate-400 hover:text-white transition-colors">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                    </form>

                    <!-- Mobile Search Toggle -->
                    <button id="mobileSearchToggle" class="md:hidden text-slate-300 hover:text-white p-2 focus:outline-none cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </button>

                    <!-- Navigation Links -->
                    <nav class="flex items-center gap-4 sm:gap-6">
                        <a href="{{ route('home') }}" class="text-sm font-medium tracking-wide {{ Route::is('home') ? 'text-white font-bold' : 'text-slate-300 hover:text-slate-100' }} transition-colors">
                            Katalog Publik
                        </a>
                        
                        @auth
                            <a href="{{ route('admin.index') }}" class="text-sm font-medium tracking-wide {{ Route::is('admin.*') ? 'text-white font-bold' : 'text-slate-300 hover:text-slate-100' }} transition-colors">
                                Dashboard Admin
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-slate-300 hover:text-netflix-red transition-colors cursor-pointer flex items-center gap-1.5">
                                    <span class="hidden sm:inline">Logout</span> <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-semibold text-white bg-netflix-red hover:bg-[#b81d24] focus:outline-none focus:ring-2 focus:ring-netflix-red rounded transition-all shadow-lg shadow-netflix-red/20">
                                Masuk <i class="fa-solid fa-chevron-right text-xs ml-1.5"></i>
                            </a>
                        @endauth
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Search Bar Drawer -->
    <div id="mobileSearchDrawer" class="hidden md:hidden fixed top-16 sm:top-20 left-0 w-full z-40 bg-[#141414]/95 border-b border-zinc-800/80 px-4 py-3 backdrop-blur-md transition-all duration-300">
        <form action="{{ route('home') }}" method="GET" class="relative">
            <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 hover:text-white transition-colors cursor-pointer focus:outline-none">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </button>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari film, genre, atau sutradara..." 
                class="block w-full pl-9 pr-10 py-2 bg-zinc-900/80 border border-zinc-800 rounded-md text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-netflix-red focus:border-netflix-red text-sm">
            @if(request('search'))
                <a href="{{ route('home') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Main Content wrapper with top padding to account for fixed navbar -->
    <main class="flex-grow w-full pb-16 pt-0">
        <!-- Toast Notification / Flash Messages -->
        <div class="fixed top-24 right-4 z-50 max-w-sm w-full space-y-3 pointer-events-none">
            @if (session('success'))
                <div class="p-4 rounded bg-[#181818] border-l-4 border-green-500 text-slate-200 flex items-start gap-3 shadow-2xl pointer-events-auto animate-fade-in translate-x-0 transition-transform duration-300">
                    <i class="fa-solid fa-circle-check text-green-500 text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <span class="font-bold text-white text-sm">Sukses</span>
                        <p class="text-xs text-slate-400 mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-500 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark text-xs"></i></button>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded bg-[#181818] border-l-4 border-netflix-red text-slate-200 flex items-start gap-3 shadow-2xl pointer-events-auto animate-fade-in translate-x-0 transition-transform duration-300">
                    <i class="fa-solid fa-circle-exclamation text-netflix-red text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <span class="font-bold text-white text-sm">Error</span>
                        <p class="text-xs text-slate-400 mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-500 hover:text-white cursor-pointer"><i class="fa-solid fa-xmark text-xs"></i></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-900 bg-netflix-black/60 py-10 text-center text-xs text-slate-500">
        <div class="max-w-4xl mx-auto px-4 space-y-6">
            <div class="flex justify-center gap-6 text-lg text-slate-400">
                <a href="https://instagram.com/tams_kaisar" target="_blank" rel="noopener noreferrer" class="hover:text-[#E1306C] transition-colors duration-300" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://github.com/codewithtama" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-300" title="GitHub"><i class="fa-brands fa-github"></i></a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-left max-w-2xl mx-auto text-slate-400">
                <a href="#" class="hover:underline">Deskripsi Audio</a>
                <a href="#" class="hover:underline">Pusat Bantuan</a>
                <a href="#" class="hover:underline">Kartu Hadiah</a>
                <a href="#" class="hover:underline">Hubungan Investor</a>
                <a href="#" class="hover:underline">Karir</a>
                <a href="#" class="hover:underline">Ketentuan Penggunaan</a>
                <a href="#" class="hover:underline">Pernyataan Privasi</a>
                <a href="#" class="hover:underline">Informasi Perusahaan</a>
            </div>
            <p class="pt-4">&copy; 2026 CineManage. UAS Rekayasa Web - NIM 241011750041.</p>
        </div>
    </footer>

    <!-- Header Scroll Effect & Mobile Search Toggle JS -->
    <script>
        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.remove('bg-gradient-to-b', 'from-black/80', 'to-transparent');
                header.classList.add('bg-netflix-black', 'shadow-lg');
            } else {
                header.classList.remove('bg-netflix-black', 'shadow-lg');
                header.classList.add('bg-gradient-to-b', 'from-black/80', 'to-transparent');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobileSearchToggle');
            const drawer = document.getElementById('mobileSearchDrawer');
            if (toggleBtn && drawer) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    drawer.classList.toggle('hidden');
                    const input = drawer.querySelector('input');
                    if (!drawer.classList.contains('hidden') && input) {
                        input.focus();
                    }
                });

                // Close drawer on click outside
                document.addEventListener('click', function(e) {
                    if (!drawer.classList.contains('hidden') && !drawer.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
                        drawer.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>

