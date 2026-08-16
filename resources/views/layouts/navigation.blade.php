<nav
    class="sticky top-0 z-50 bg-white/80 dark:bg-stone-900/80 backdrop-blur-xl border-b border-stone-200 dark:border-stone-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20 gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 sm:gap-3 group min-w-0">
                    <div
                        class="bg-primary-600 p-2 sm:p-2.5 rounded-xl shadow-brand group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-xl font-bold text-stone-900 dark:text-white tracking-tight leading-tight">DukanIQ</h1>
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse flex-shrink-0"></span>
                            <p class="text-xs font-medium text-stone-500 dark:text-stone-400 truncate">{{ Auth::user()->name }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">

                <button @click="toggleTheme()"
                    class="p-2 sm:p-2.5 rounded-xl text-stone-500 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 border border-transparent hover:border-stone-200 dark:hover:border-stone-700"
                    aria-label="Toggle Theme">
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <a href="{{ route('profile.edit') }}"
                    class="p-2 sm:p-2.5 rounded-xl transition-all duration-200 border
                    {{ request()->routeIs('profile.edit')
                        ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 border-primary-100 dark:border-primary-800/40'
                        : 'text-stone-500 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800 hover:text-primary-600 dark:hover:text-primary-400 border-transparent hover:border-stone-200 dark:hover:border-stone-700' }}"
                    aria-label="Profile">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>

                <div class="hidden sm:block h-8 w-px bg-stone-200 dark:bg-stone-800"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 border whitespace-nowrap
                               text-stone-700 bg-white border-stone-200 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 hover:shadow-sm
                               dark:bg-stone-800 dark:border-stone-700 dark:text-stone-300 dark:hover:bg-rose-900/20 dark:hover:border-rose-800 dark:hover:text-rose-400">
                        <span class="hidden sm:inline">Log Out</span>
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="sr-only sm:hidden">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
