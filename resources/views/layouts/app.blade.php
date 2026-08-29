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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        // Dark-first: default to dark unless the user has explicitly chosen light.
        if (localStorage.getItem('theme') !== 'light') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="h-full font-sans antialiased bg-ink-50 text-ink-900 dark:bg-ink-950 dark:text-ink-50 transition-colors duration-300">

    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-20 w-[500px] h-[500px] bg-aurora-violet/10 dark:bg-aurora-violet/15 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 -right-32 w-[500px] h-[500px] bg-fuchsia-500/10 dark:bg-fuchsia-500/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-[120px]"></div>
    </div>

    @include('layouts.sidebar')
    @include('layouts.mobile-nav')

    <div class="min-h-screen flex flex-col lg:pl-64">
        <main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 pb-28 lg:pb-8">
            {{ $slot }}
        </main>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-cloak
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-24 lg:bottom-6 right-4 left-4 sm:left-auto sm:right-6 z-50 flex items-center sm:w-full sm:max-w-sm p-4 bg-white/90 dark:bg-ink-850/90 glass-panel rounded-2xl shadow-glass border border-ink-200/70 dark:border-ink-700/70"
            role="alert">

            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-aurora-gradient rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                </svg>
                <span class="sr-only">Check icon</span>
            </div>

            <div class="ml-3 text-sm font-medium text-ink-800 dark:text-ink-100">
                {{ session('success') }}
            </div>

            <button type="button" @click="show = false"
                class="ml-auto -mx-1.5 -my-1.5 bg-transparent text-ink-400 hover:text-ink-900 rounded-lg focus:ring-2 focus:ring-ink-300 p-1.5 hover:bg-ink-100 inline-flex items-center justify-center h-8 w-8 dark:text-ink-500 dark:hover:text-white dark:hover:bg-ink-800 transition-colors">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif

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
