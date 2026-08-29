<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="themeHandler()"
    :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DukanIQ') }} - Smart Shop Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

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
    <script>
        if (localStorage.getItem('theme') !== 'light') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
    </style>
</head>

<body
    class="antialiased font-sans bg-ink-50 text-ink-900 dark:bg-ink-950 dark:text-ink-50 transition-colors duration-300 overflow-x-hidden">

    <nav
        class="fixed top-0 w-full z-50 bg-white/70 dark:bg-ink-900/60 glass-panel border-b border-ink-200/70 dark:border-ink-800/70 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20 gap-2">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-aurora-gradient shadow-glow-violet flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-lg sm:text-xl font-bold text-ink-900 dark:text-white tracking-tight truncate">DukanIQ</span>
                </div>

                <div class="flex items-center gap-1 sm:gap-4 flex-shrink-0">
                    <button @click="toggleTheme()"
                        class="p-2 sm:mr-2 rounded-full text-ink-500 hover:bg-ink-100/70 dark:hover:bg-ink-800/60 transition-colors focus:outline-none"
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
                            class="inline-flex px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-white bg-aurora-gradient hover:brightness-110 rounded-xl shadow-glow-violet transition-all duration-300 transform hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-semibold text-ink-600 hover:text-ink-900 dark:text-ink-300 dark:hover:text-white transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-white bg-aurora-gradient hover:brightness-110 rounded-xl shadow-glow-violet transition-all duration-300 transform hover:-translate-y-0.5">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-28 pb-20 sm:pt-32 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-ink-50 dark:bg-ink-950"></div>
            <div class="absolute top-20 left-10 w-[550px] h-[550px] bg-aurora-violet/20 dark:bg-aurora-violet/25 rounded-full blur-[120px] opacity-80"></div>
            <div class="absolute bottom-20 right-10 w-[550px] h-[550px] bg-fuchsia-500/15 dark:bg-fuchsia-500/20 rounded-full blur-[120px] opacity-80"></div>
            <div class="absolute top-1/2 right-1/3 w-[350px] h-[350px] bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-[100px] opacity-70"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <div class="text-center lg:text-left relative z-10">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 dark:bg-ink-900/60 glass-panel border border-ink-200/70 dark:border-ink-700/70 mb-8 shadow-sm">
                        <svg class="w-4 h-4 text-aurora-violet dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-sm font-semibold text-ink-700 dark:text-ink-300">Built for everyday shopkeepers</span>
                    </div>

                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-ink-900 dark:text-white leading-[1.15] mb-6 tracking-tight">
                        Manage your shop<br />
                        <span class="text-aurora">the smart way</span>
                    </h1>

                    <p
                        class="text-lg sm:text-xl text-ink-600 dark:text-ink-400 leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                        Stop using notebooks. Track sales, expenses, stock, and udhari in real-time with a platform
                        built for growth.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="group inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-aurora-gradient hover:brightness-110 rounded-2xl shadow-glow-violet-lg transition-all duration-300 transform hover:-translate-y-1">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-ink-700 dark:text-ink-200 bg-white/70 dark:bg-ink-900/50 glass-panel border border-ink-200/70 dark:border-ink-700/70 rounded-2xl hover:border-aurora-violet/40 transition-all duration-300">
                            View Features
                        </a>
                    </div>
                </div>

                <div class="relative h-[480px] hidden lg:block select-none">

                    <div
                        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[380px] bg-white/80 dark:bg-ink-900/70 glass-panel p-8 rounded-3xl shadow-glow-violet-lg border border-ink-200/70 dark:border-ink-700/70 z-20 animate-float transition-colors duration-300">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <p
                                    class="text-sm text-ink-500 dark:text-ink-400 font-semibold uppercase tracking-wider mb-1">
                                    Net Profit</p>
                                <h3 class="text-4xl font-extrabold text-ink-900 dark:text-white">₹12,450</h3>
                            </div>
                            <div class="bg-aurora-gradient p-4 rounded-2xl text-white shadow-glow-violet">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="h-4 w-full bg-ink-100 dark:bg-ink-800 rounded-full overflow-hidden mb-4">
                            <div class="h-full w-[70%] bg-aurora-gradient rounded-full"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-xs font-semibold text-aurora-violet dark:text-fuchsia-400 bg-aurora-gradient-soft px-3 py-1 rounded-full">
                                +15% vs yesterday</p>
                        </div>
                    </div>

                    <div
                        class="absolute top-12 -right-6 w-72 bg-white/80 dark:bg-ink-900/70 glass-panel p-5 rounded-2xl shadow-glass border border-ink-200/70 dark:border-ink-700/70 z-10 animate-float-delayed transition-colors duration-300">
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-12 h-12 rounded-xl bg-mint-50 dark:bg-mint-500/10 flex items-center justify-center text-mint-500 border border-mint-100 dark:border-mint-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-xs text-ink-500 dark:text-ink-400 font-semibold uppercase tracking-wider mb-0.5">
                                    New Sale</p>
                                <p class="text-lg font-bold text-mint-500">+ ₹450.00</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-20 -left-6 w-72 bg-white/80 dark:bg-ink-900/70 glass-panel p-5 rounded-2xl shadow-glass border border-ink-200/70 dark:border-ink-700/70 z-10 animate-float transition-colors duration-300"
                        style="animation-delay: 3s;">
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-500 border border-rose-100 dark:border-rose-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-xs text-ink-500 dark:text-ink-400 font-semibold uppercase tracking-wider mb-0.5">
                                    Expense</p>
                                <p class="text-lg font-bold text-rose-500">- ₹1,200.00</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 sm:py-24 bg-white/50 dark:bg-ink-900/30 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 sm:mb-20">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-ink-900 dark:text-white mb-4 tracking-tight">
                    Everything you need</h2>
                <p class="text-lg sm:text-xl text-ink-600 dark:text-ink-400">Clear insights without the complexity</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div
                    class="group p-8 rounded-3xl bg-white/70 dark:bg-ink-900/50 glass-panel border border-ink-200/70 dark:border-ink-800/70 hover:border-aurora-violet/40 hover:shadow-glow-violet transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-aurora-gradient rounded-2xl flex items-center justify-center text-white mb-6 shadow-glow-violet group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-ink-900 dark:text-white mb-3">Lightning Fast</h3>
                    <p class="text-ink-600 dark:text-ink-400 leading-relaxed">Record transactions in seconds with
                        an optimized, thumb-friendly interface designed for speed.</p>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-white/70 dark:bg-ink-900/50 glass-panel border border-ink-200/70 dark:border-ink-800/70 hover:border-mint-500/40 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-mint-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-mint-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-ink-900 dark:text-white mb-3">Stock &amp; Udhari</h3>
                    <p class="text-ink-600 dark:text-ink-400 leading-relaxed">Track what's on your shelves and who
                        owes what — a full financial picture, not just cash in and out.</p>
                </div>

                <div
                    class="group p-8 rounded-3xl bg-white/70 dark:bg-ink-900/50 glass-panel border border-ink-200/70 dark:border-ink-800/70 hover:border-rose-500/40 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-rose-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-ink-900 dark:text-white mb-3">Track Expenses</h3>
                    <p class="text-ink-600 dark:text-ink-400 leading-relaxed">Never miss small costs that silently
                        eat into your profit margins. Track every single rupee.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 sm:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-ink-50 dark:bg-ink-950 -z-10"></div>
        <div class="absolute bottom-0 left-0 w-full h-full bg-aurora-gradient-soft opacity-60"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-ink-900 dark:text-white mb-6 tracking-tight">Ready to
                modernize?</h2>
            <p class="text-lg sm:text-xl text-ink-600 dark:text-ink-400 mb-10 max-w-2xl mx-auto">Setup takes less
                than 2 minutes — no credit card required.</p>
            <a href="{{ route('register') }}"
                class="inline-block px-10 sm:px-12 py-4 sm:py-5 text-base sm:text-lg font-semibold text-white bg-aurora-gradient hover:brightness-110 rounded-2xl shadow-glow-violet-lg transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                Get Started for Free
            </a>
        </div>
    </section>

    <footer
        class="border-t border-ink-200/70 dark:border-ink-800/70 bg-white/50 dark:bg-ink-900/30 py-10 sm:py-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-aurora-gradient shadow-glow-violet flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="font-bold text-ink-900 dark:text-white text-lg">DukanIQ</span>
            </div>

            <div class="flex gap-8 text-ink-600 dark:text-ink-400 text-sm font-medium">
                <a href="#" class="hover:text-aurora-violet dark:hover:text-fuchsia-400 transition-colors">Privacy</a>
                <a href="#" class="hover:text-aurora-violet dark:hover:text-fuchsia-400 transition-colors">Terms</a>
                <a href="#" class="hover:text-aurora-violet dark:hover:text-fuchsia-400 transition-colors">Support</a>
            </div>

            <p class="text-ink-500 dark:text-ink-500 text-sm">© {{ date('Y') }} DukanIQ Inc.</p>
        </div>
    </footer>
</body>

</html>
