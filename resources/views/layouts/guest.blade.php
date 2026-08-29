<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth" x-data="themeHandler()"
    :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DukanIQ') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') !== 'light') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans text-ink-900 antialiased bg-ink-50 dark:bg-ink-950 dark:text-ink-50 transition-colors duration-300">
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center px-4 pt-10 sm:pt-6 pb-10 overflow-hidden">
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 left-1/4 w-[500px] h-[500px] bg-aurora-violet/15 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-fuchsia-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute top-1/2 right-10 w-[300px] h-[300px] bg-amber-500/10 rounded-full blur-[100px]"></div>
        </div>

        <button @click="toggleTheme()"
            class="fixed top-4 right-4 sm:top-6 sm:right-6 z-20 p-2.5 rounded-xl bg-white/70 dark:bg-ink-900/70 glass-panel shadow-sm border border-ink-200/70 dark:border-ink-700/70 text-ink-500 dark:text-ink-400 hover:text-aurora-violet dark:hover:text-fuchsia-400 transition-all duration-200"
            aria-label="Toggle Dark Mode">
            <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
            </svg>
            <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                </path>
            </svg>
        </button>

        <div
            class="w-full sm:max-w-md px-6 py-8 sm:px-8 sm:py-10 bg-white/80 dark:bg-ink-900/60 glass-panel shadow-glass border border-ink-200/70 dark:border-ink-800/70 rounded-3xl z-10 relative transition-colors duration-300">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center text-xs text-ink-400 dark:text-ink-600 z-10 font-medium">
            &copy; {{ date('Y') }} DukanIQ. Secure &amp; Encrypted.
        </div>
    </div>

    <script>
        function themeHandler() {
            return {
                isDark: localStorage.getItem('theme') !== 'light',
                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }
    </script>
</body>

</html>
