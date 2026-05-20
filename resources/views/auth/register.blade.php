<x-guest-layout>
    <div class="mb-7 text-center">
        <h1 class="display-font text-4xl text-gray-900">Create Account</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Join Love Bite and start ordering 🍛</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5">Full Name</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border bg-white/70 font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent placeholder:text-gray-300 {{ $errors->get('name') ? 'border-red-400 bg-red-50/30' : 'border-gray-200 hover:border-orange-200' }}"
                    placeholder="e.g. Rahul Sharma">
            </div>
            @error('name')
                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
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
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full pl-10 pr-12 py-3 text-sm rounded-xl border bg-white/70 font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent placeholder:text-gray-300 {{ $errors->get('password') ? 'border-red-400 bg-red-50/30' : 'border-gray-200 hover:border-orange-200' }}"
                    placeholder="Min. 8 characters">
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-3.5 flex items-center text-gray-400 hover:text-orange-500 transition" tabindex="-1">
                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>

            {{-- Password strength indicator --}}
            <div class="mt-2 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div id="strength-bar" class="h-full rounded-full transition-all duration-300 w-0 bg-red-400"></div>
            </div>
            <p id="strength-text" class="text-xs text-gray-400 mt-1 font-medium"></p>

            @error('password')
                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1.5">Confirm Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full pl-10 pr-12 py-3 text-sm rounded-xl border bg-white/70 font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent placeholder:text-gray-300 {{ $errors->get('password_confirmation') ? 'border-red-400 bg-red-50/30' : 'border-gray-200 hover:border-orange-200' }}"
                    placeholder="Re-enter your password">
                <span id="match-icon" class="absolute inset-y-0 right-3.5 flex items-center hidden">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full py-3.5 px-6 text-sm font-bold text-white rounded-xl bg-orange-600 hover:bg-orange-700 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-orange-500/25 mt-2 flex items-center justify-center gap-2">
            Create My Account
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-3 my-6">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Already a member?</span>
        <div class="flex-1 h-px bg-gray-200"></div>
    </div>

    {{-- Login link --}}
    <a href="{{ route('login') }}"
        class="flex items-center justify-center gap-2 w-full py-3.5 px-6 text-sm font-bold text-orange-700 rounded-xl border-2 border-orange-200 bg-orange-50 hover:bg-orange-100 hover:border-orange-300 transition-all duration-200 active:scale-[0.98]">
        Sign in instead
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
    </a>

    <script>
        // Show/hide password
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

        // Password strength meter
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            const levels = [
                { w: '0%', color: 'bg-gray-200', text: '' },
                { w: '25%', color: 'bg-red-400', text: 'Weak' },
                { w: '50%', color: 'bg-amber-400', text: 'Fair' },
                { w: '75%', color: 'bg-yellow-400', text: 'Good' },
                { w: '100%', color: 'bg-green-500', text: 'Strong 💪' },
            ];
            const level = val.length === 0 ? levels[0] : levels[score];
            strengthBar.style.width = level.w;
            strengthBar.className = `h-full rounded-full transition-all duration-300 ${level.color}`;
            strengthText.textContent = level.text;
            strengthText.className = `text-xs mt-1 font-medium ${score <= 1 ? 'text-red-400' : score <= 2 ? 'text-amber-500' : score === 3 ? 'text-yellow-500' : 'text-green-600'}`;
        });

        // Confirm password match indicator
        const confirmInput = document.getElementById('password_confirmation');
        const matchIcon = document.getElementById('match-icon');
        confirmInput.addEventListener('input', () => {
            if (confirmInput.value.length > 0 && confirmInput.value === passwordInput.value) {
                matchIcon.classList.remove('hidden');
                confirmInput.classList.remove('border-gray-200');
                confirmInput.classList.add('border-green-400');
            } else {
                matchIcon.classList.add('hidden');
                confirmInput.classList.add('border-gray-200');
                confirmInput.classList.remove('border-green-400');
            }
        });
    </script>
</x-guest-layout>
