@php
    $navItems = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'grid'],
        ['route' => 'stock.index', 'label' => 'Stock', 'icon' => 'box'],
        ['route' => 'udhari.index', 'label' => 'Udhari', 'icon' => 'handshake'],
        ['route' => 'reports.index', 'label' => 'Reports', 'icon' => 'chart'],
    ];

    $icons = [
        'grid' => 'M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z',
        'box' => 'M20 7L12 3 4 7m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'handshake' => 'M8 12l3 3 6-6M6 8l-3 3 5 5 2-2m8-6l3 3-5 5-2-2M9 11l2-2 4 4-2 2',
        'chart' => 'M8 17V9m4 8V5m4 12v-6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z',
    ];
@endphp

<aside class="hidden lg:flex flex-col fixed inset-y-0 left-0 w-64 z-40 bg-white/70 dark:bg-ink-900/60 backdrop-blur-2xl border-r border-ink-200/70 dark:border-ink-800/70">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 h-20 flex-shrink-0">
        <div class="w-10 h-10 rounded-xl bg-aurora-gradient shadow-glow-violet flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <span class="text-lg font-bold text-ink-900 dark:text-white tracking-tight">DukanIQ</span>
    </a>

    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto no-scrollbar">
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['route']) || request()->routeIs(str($item['route'])->before('.') . '.*'); @endphp
            <a href="{{ route($item['route']) }}"
                class="group relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
                {{ $active
                    ? 'text-white bg-aurora-gradient shadow-glow-violet'
                    : 'text-ink-500 dark:text-ink-400 hover:text-ink-900 dark:hover:text-white hover:bg-ink-100/70 dark:hover:bg-ink-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$item['icon']] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="px-4 pb-4 space-y-1 border-t border-ink-200/70 dark:border-ink-800/70 pt-4">
        <button @click="toggleTheme()"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-ink-500 dark:text-ink-400 hover:text-ink-900 dark:hover:text-white hover:bg-ink-100/70 dark:hover:bg-ink-800/60 transition-all duration-200">
            <svg x-show="!isDark" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg x-show="isDark" x-cloak class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <span x-text="isDark ? 'Dark Mode' : 'Light Mode'"></span>
        </button>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
            {{ request()->routeIs('profile.edit')
                ? 'text-aurora-violet dark:text-fuchsia-400 bg-aurora-gradient-soft'
                : 'text-ink-500 dark:text-ink-400 hover:text-ink-900 dark:hover:text-white hover:bg-ink-100/70 dark:hover:bg-ink-800/60' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="truncate">{{ Auth::user()->name }}</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-ink-500 dark:text-ink-400 hover:text-rose-500 hover:bg-rose-500/10 transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
