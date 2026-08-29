<x-guest-layout>
    <div class="text-center mb-8">
        <a href="/"
            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-aurora-gradient text-white shadow-glow-violet mb-5 hover:scale-105 transition-transform duration-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </a>
        <h2 class="text-2xl font-semibold text-ink-900 dark:text-white tracking-tight">Create Account</h2>
        <p class="text-ink-500 dark:text-ink-400 text-sm mt-2">Join thousands of smart shopkeepers - Email verification required</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name"
                class="block text-xs font-semibold text-ink-500 dark:text-ink-400 uppercase tracking-wide mb-1.5 ml-1">Shop
                Owner Name</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-ink-400 group-focus-within:text-violet-500 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name"
                    class="w-full pl-10 pr-4 py-3 bg-ink-50 dark:bg-ink-900/50 border border-ink-200 dark:border-ink-700 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 dark:focus:border-violet-400 text-ink-900 dark:text-white placeholder-ink-400 dark:placeholder-ink-500 transition-all duration-200 sm:text-sm"
                    placeholder="e.g. Abdul Aziz">
            </div>
            @error('name')
                <span class="flex items-center gap-1 text-rose-500 dark:text-rose-400 text-xs mt-1.5 ml-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div>
            <label for="email"
                class="block text-xs font-semibold text-ink-500 dark:text-ink-400 uppercase tracking-wide mb-1.5 ml-1">Email
                Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-ink-400 group-focus-within:text-violet-500 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username"
                    class="w-full pl-10 pr-4 py-3 bg-ink-50 dark:bg-ink-900/50 border border-ink-200 dark:border-ink-700 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 dark:focus:border-violet-400 text-ink-900 dark:text-white placeholder-ink-400 dark:placeholder-ink-500 transition-all duration-200 sm:text-sm"
                    placeholder="you@example.com">
            </div>
            @error('email')
                <span class="flex items-center gap-1 text-rose-500 dark:text-rose-400 text-xs mt-1.5 ml-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div x-data="{ show: false }">
            <label for="password"
                class="block text-xs font-semibold text-ink-500 dark:text-ink-400 uppercase tracking-wide mb-1.5 ml-1">Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-ink-400 group-focus-within:text-violet-500 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                    autocomplete="new-password"
                    class="w-full pl-10 pr-12 py-3 bg-ink-50 dark:bg-ink-900/50 border border-ink-200 dark:border-ink-700 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 dark:focus:border-violet-400 text-ink-900 dark:text-white placeholder-ink-400 dark:placeholder-ink-500 transition-all duration-200 sm:text-sm"
                    placeholder="••••••••">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-ink-400 hover:text-ink-600 dark:hover:text-ink-300 focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password')
                <span class="flex items-center gap-1 text-rose-500 dark:text-rose-400 text-xs mt-1.5 ml-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div x-data="{ show: false }">
            <label for="password_confirmation"
                class="block text-xs font-semibold text-ink-500 dark:text-ink-400 uppercase tracking-wide mb-1.5 ml-1">Confirm
                Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-ink-400 group-focus-within:text-violet-500 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation"
                    required autocomplete="new-password"
                    class="w-full pl-10 pr-12 py-3 bg-ink-50 dark:bg-ink-900/50 border border-ink-200 dark:border-ink-700 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 dark:focus:border-violet-400 text-ink-900 dark:text-white placeholder-ink-400 dark:placeholder-ink-500 transition-all duration-200 sm:text-sm"
                    placeholder="••••••••">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-ink-400 hover:text-ink-600 dark:hover:text-ink-300 focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <span class="flex items-center gap-1 text-rose-500 dark:text-rose-400 text-xs mt-1.5 ml-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-aurora-gradient hover:brightness-110 shadow-glow-violet transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-violet dark:focus:ring-offset-ink-900">
                {{ __('Register Shop') }}
            </button>
        </div>

        <div class="text-center pt-6 border-t border-ink-100 dark:border-ink-700/50 mt-6">
            <p class="text-sm text-ink-500 dark:text-ink-400">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-500 dark:hover:text-violet-300 transition-colors ml-1">
                    Log in here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
