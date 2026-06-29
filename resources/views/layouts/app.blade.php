<!DOCTYPE html>
<html lang="id" class="h-full bg-[#141414] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

                <!-- Navigation Links -->
                <nav class="flex items-center gap-6">
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
    </header>

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
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-youtube"></i></a>
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

    <!-- Header Scroll Effect JS -->
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
    </script>
    @yield('scripts')
</body>
</html>

