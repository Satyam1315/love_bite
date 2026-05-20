<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Love Bite') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen">

    {{-- Decorative background blobs --}}
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-orange-100 rounded-full opacity-60 animate-blob"></div>
        <div class="absolute top-1/2 -right-32 w-[400px] h-[400px] bg-amber-100 rounded-full opacity-50 animate-blob" style="animation-delay:3s;"></div>
        <div class="absolute bottom-0 left-1/2 w-[350px] h-[350px] bg-orange-50 rounded-full opacity-40 animate-blob" style="animation-delay:6s;"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center group">
            <span class="display-font text-5xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 group-hover:from-orange-700 group-hover:to-amber-600 transition-all duration-300">Love Bite</span>
            <span class="text-xs font-bold text-orange-400 uppercase tracking-widest mt-1">Every bite, a memory</span>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 p-8 scale-in">
            {{ $slot }}
        </div>

        {{-- Footer links --}}
        <p class="mt-8 text-xs text-gray-400 text-center">
            © {{ date('Y') }} Love Bite. Made with ❤️ &nbsp;·&nbsp;
            <a href="{{ route('about') }}" class="hover:text-orange-500 transition">About Us</a>
        </p>
    </div>
</body>
</html>
