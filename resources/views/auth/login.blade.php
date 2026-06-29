@extends('layouts.app')

@section('title', 'Admin Login')

@section('styles')
<style>
    /* Full height background with movie-themed image and heavy vignette */
    .login-bg-container {
        position: fixed;
        inset: 0;
        z-index: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.4) 40%, rgba(0, 0, 0, 0.8) 100%), 
                    url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        filter: saturate(0.85);
    }
</style>
@endsection

@section('content')
<!-- Background container -->
<div class="login-bg-container"></div>

<!-- Login Card Wrapper -->
<div class="relative z-10 flex items-center justify-center min-h-[80vh] px-4 sm:px-6 py-12">
    <div class="max-w-[450px] w-full bg-black/75 border border-zinc-900 rounded-lg p-8 sm:p-14 shadow-2xl backdrop-blur-sm">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white tracking-wide">
                Masuk Admin
            </h2>
            <p class="mt-2 text-xs text-slate-400">
                Gunakan kredensial administrator Anda untuk masuk ke panel kontrol bioskop.
            </p>
        </div>

        <!-- Form -->
        <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <!-- Username Field -->
                <div class="space-y-1">
                    <label for="username" class="block text-xs font-semibold text-slate-400">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 text-xs">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                            placeholder="Username admin"
                            @class([
                                'block w-full pl-10 pr-4 py-3 bg-zinc-800 border border-transparent rounded text-white placeholder-zinc-500 focus:outline-none focus:bg-zinc-700 focus:ring-1 transition-all text-sm',
                                'border-netflix-red focus:ring-netflix-red' => $errors->has('username'),
                                'focus:ring-netflix-red' => !$errors->has('username'),
                            ])>
                    </div>
                    @error('username')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-1">
                    <label for="password" class="block text-xs font-semibold text-slate-400">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" name="password" type="password" required
                            placeholder="Password admin"
                            @class([
                                'block w-full pl-10 pr-4 py-3 bg-zinc-800 border border-transparent rounded text-white placeholder-zinc-500 focus:outline-none focus:bg-zinc-700 focus:ring-1 transition-all text-sm',
                                'border-netflix-red focus:ring-netflix-red' => $errors->has('password'),
                                'focus:ring-netflix-red' => !$errors->has('password'),
                            ])>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Remember Me & Submit -->
            <div class="space-y-6 pt-2">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center cursor-pointer select-none">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4.5 w-4.5 rounded bg-zinc-900 border-zinc-700 text-netflix-red focus:ring-netflix-red focus:ring-offset-black cursor-pointer">
                        <span class="ml-2">Ingat saya</span>
                    </label>
                    <a href="#" class="hover:underline hover:text-white">Butuh bantuan?</a>
                </div>

                <button type="submit" 
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded text-white bg-netflix-red hover:bg-[#b81d24] focus:outline-none focus:ring-2 focus:ring-netflix-red focus:ring-offset-2 focus:ring-offset-black transition-all shadow-lg shadow-netflix-red/20 cursor-pointer">
                    Masuk <i class="fa-solid fa-arrow-right-to-bracket ml-2 mt-0.5"></i>
                </button>
            </div>
        </form>

        <div class="mt-10 text-xs text-slate-500 border-t border-zinc-900 pt-6">
            <span class="block">Baru di CineManage? Silakan hubungi tim IT Administrator kampus untuk pendaftaran akun admin baru.</span>
        </div>
    </div>
</div>
@endsection

