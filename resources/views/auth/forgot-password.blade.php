<x-guest-layout>
    <div class="mb-7 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 mb-4">
            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
        </div>
        <h1 class="display-font text-4xl text-gray-900">Forgot Password?</h1>
        <p class="text-sm text-gray-500 mt-1.5 font-medium leading-relaxed max-w-xs mx-auto">No worries! Enter your email and we'll send you a reset link.</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm font-semibold text-green-700 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border bg-white/70 font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent placeholder:text-gray-300 {{ $errors->get('email') ? 'border-red-400 bg-red-50/30' : 'border-gray-200 hover:border-orange-200' }}"
                    placeholder="you@example.com">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit"
            class="w-full py-3.5 px-6 text-sm font-bold text-white rounded-xl bg-orange-600 hover:bg-orange-700 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
            Send Reset Link
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 transition inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Login
        </a>
    </div>
</x-guest-layout>
