<x-guest-layout>
    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm font-semibold text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-7 text-center">
        <h1 class="display-font text-4xl text-gray-900">Welcome Back</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Sign in to continue your food journey 🍽️</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
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

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 transition">Forgot password?</a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full pl-10 pr-12 py-3 text-sm rounded-xl border bg-white/70 font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent placeholder:text-gray-300 {{ $errors->get('password') ? 'border-red-400 bg-red-50/30' : 'border-gray-200 hover:border-orange-200' }}"
                    placeholder="Enter your password">
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-3.5 flex items-center text-gray-400 hover:text-orange-500 transition" tabindex="-1">
                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember Me --}}
        <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer group">
            <input id="remember_me" type="checkbox" name="remember"
                class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-400 cursor-pointer">
            <span class="text-sm text-gray-600 font-medium group-hover:text-gray-800 transition">Keep me signed in</span>
        </label>

        {{-- Submit --}}
        <button type="submit"
            class="w-full py-3.5 px-6 text-sm font-bold text-white rounded-xl bg-orange-600 hover:bg-orange-700 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-orange-500/25 mt-2 flex items-center justify-center gap-2">
            Sign In
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-3 my-6">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">New here?</span>
        <div class="flex-1 h-px bg-gray-200"></div>
    </div>

    {{-- Register link --}}
    <a href="{{ route('register') }}"
        class="flex items-center justify-center gap-2 w-full py-3.5 px-6 text-sm font-bold text-orange-700 rounded-xl border-2 border-orange-200 bg-orange-50 hover:bg-orange-100 hover:border-orange-300 transition-all duration-200 active:scale-[0.98]">
        Create a free account
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
    </a>

    <script>
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const hiddenPath = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        const visiblePath = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.innerHTML = isHidden ? hiddenPath : visiblePath;
        });
    </script>
</x-guest-layout>
