<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="themeHandler()"
    :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DukanIQ') }} - Smart Shop Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script>
        function themeHandler() {
            return {
                isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches),
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
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 7s ease-in-out infinite;
            animation-delay: 2s;
        }

        /* Text Gradients */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
        }

        .dark .text-gradient {
            background-image: linear-gradient(to right, #818cf8, #a78bfa);
        }
    </style>
</head>

<body
    class="antialiased font-sans bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300 overflow-x-hidden">

    <nav
        class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-indigo-600 to-violet-600 p-2.5 rounded-xl shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">DukanIQ</span>
                </div>

                <div class="flex items-center gap-4">
                    <button @click="toggleTheme()"
                        class="p-2 mr-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none"
                        aria-label="Toggle Dark Mode">
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

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="hidden sm:inline-flex px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-white transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                            class="hidden sm:inline-flex px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-slate-50 dark:bg-slate-950"></div>
            <div
                class="absolute top-20 left-10 w-[500px] h-[500px] bg-indigo-200/40 dark:bg-indigo-900/20 rounded-full blur-[100px] opacity-70">
            </div>
            <div
                class="absolute bottom-20 right-10 w-[500px] h-[500px] bg-violet-200/40 dark:bg-violet-900/20 rounded-full blur-[100px] opacity-70">
            </div>
            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div class="text-center lg:text-left relative z-10">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/50 dark:bg-slate-800/50 border border-indigo-100 dark:border-slate-700 backdrop-blur-sm mb-8 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Trusted by 500+
                            Shops</span>
                    </div>

                    <h1
                        class="text-5xl sm:text-6xl lg:text-7xl font-black text-slate-900 dark:text-white leading-[1.1] mb-6 tracking-tight">
                        Manage your shop<br />
                        <span class="text-gradient">the smart way</span>
                    </h1>

                    <p
                        class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                        Stop using notebooks. Track sales, expenses, and daily profits in real-time with our intelligent
                        platform built for growth.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="group inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 rounded-2xl shadow-xl shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-1">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl hover:border-indigo-300 dark:hover:border-indigo-500 hover:bg-indigo-50/50 dark:hover:bg-slate-700/50 transition-all duration-300">
                            View Features
                        </a>
                    </div>
                </div>

                <div class="relative h-[500px] hidden lg:block select-none">

                    <div
                        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[380px] bg-white dark:bg-slate-800 p-8 rounded-[2rem] shadow-2xl shadow-indigo-500/10 dark:shadow-indigo-900/20 border border-slate-100 dark:border-slate-700 z-20 animate-float transition-colors duration-300">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <p
                                    class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">
                                    Net Profit</p>
                                <h3 class="text-4xl font-black text-slate-900 dark:text-white">₹12,450</h3>
                            </div>
                            <div
                                class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="h-4 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden mb-4">
                            <div
                                class="h-full w-[70%] bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full shadow-[0_0_20px_rgba(99,102,241,0.5)]">
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <p
                                class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 rounded-full">
                                +15% Growth</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">vs yesterday</p>
                        </div>
                    </div>

                    <div
                        class="absolute top-12 -right-6 w-72 bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-black/30 border border-slate-100 dark:border-slate-700 z-10 animate-float-delayed backdrop-blur-sm bg-opacity-90 dark:bg-opacity-90 transition-colors duration-300">
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                                    New Sale</p>
                                <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">+ ₹450.00</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-20 -left-6 w-72 bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-black/30 border border-slate-100 dark:border-slate-700 z-10 animate-float backdrop-blur-sm bg-opacity-90 dark:bg-opacity-90 transition-colors duration-300"
                        style="animation-delay: 3s;">
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                                    Expense</p>
                                <p class="text-lg font-black text-rose-600 dark:text-rose-400">- ₹1,200.00</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">
                    Everything you need</h2>
                <p class="text-xl text-slate-600 dark:text-slate-400">Clear insights without the complexity</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="group p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-500 hover:bg-white dark:hover:bg-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 dark:hover:shadow-indigo-900/20 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Lightning Fast</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Record transactions in seconds with
                        our optimized, thumb-friendly interface designed for speed.</p>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-500 hover:bg-white dark:hover:bg-slate-800 hover:shadow-2xl hover:shadow-emerald-500/10 dark:hover:shadow-emerald-900/20 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Daily Reports</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Automatic profit calculation at the
                        end of every day. No manual work, just clear insights.</p>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-rose-300 dark:hover:border-rose-500 hover:bg-white dark:hover:bg-slate-800 hover:shadow-2xl hover:shadow-rose-500/10 dark:hover:shadow-rose-900/20 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-rose-600 to-pink-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Track Expenses</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Never miss small costs that silently
                        eat into your profit margins. Track every single rupee.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-50 dark:bg-slate-950 -z-10"></div>
        <div
            class="absolute bottom-0 left-0 w-full h-full bg-gradient-to-t from-indigo-50 to-transparent dark:from-slate-900 dark:to-transparent opacity-50">
        </div>

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Ready to
                modernize?</h2>
            <p class="text-xl text-slate-600 dark:text-slate-400 mb-10 max-w-2xl mx-auto">Join the new wave of smart
                shopkeepers. Setup takes less than 2 minutes.</p>
            <a href="{{ route('register') }}"
                class="inline-block px-12 py-5 text-lg font-bold text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 rounded-2xl shadow-2xl shadow-indigo-500/30 dark:shadow-indigo-900/50 hover:shadow-indigo-500/50 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                Get Started for Free
            </a>
            <p class="mt-6 text-sm text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest">No Credit
                Card Required</p>
        </div>
    </section>

    <footer
        class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div
                    class="bg-gradient-to-br from-indigo-600 to-violet-600 p-2 rounded-xl shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="font-bold text-slate-900 dark:text-white text-lg">DukanIQ</span>
            </div>

            <div class="flex gap-8 text-slate-600 dark:text-slate-400 text-sm font-medium">
                <a href="#"
                    class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Privacy</a>
                <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Terms</a>
                <a href="#"
                    class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Support</a>
            </div>

            <p class="text-slate-500 dark:text-slate-500 text-sm">© {{ date('Y') }} DukanIQ Inc.</p>
        </div>
    </footer>
</body>

</html>
