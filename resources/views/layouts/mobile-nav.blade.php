@php
    $navItems = [
        ['route' => 'dashboard', 'label' => 'Home', 'icon' => 'grid'],
        ['route' => 'stock.index', 'label' => 'Stock', 'icon' => 'box'],
        ['route' => 'udhari.index', 'label' => 'Udhari', 'icon' => 'handshake'],
        ['route' => 'reports.index', 'label' => 'Reports', 'icon' => 'chart'],
        ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'user'],
    ];

    $icons = [
        'grid' => 'M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z',
        'box' => 'M20 7L12 3 4 7m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'handshake' => 'M8 12l3 3 6-6M6 8l-3 3 5 5 2-2m8-6l3 3-5 5-2-2M9 11l2-2 4 4-2 2',
        'chart' => 'M8 17V9m4 8V5m4 12v-6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z',
        'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    ];
@endphp

<header class="lg:hidden sticky top-0 z-40 bg-white/70 dark:bg-ink-900/60 backdrop-blur-2xl border-b border-ink-200/70 dark:border-ink-800/70">
    <div class="flex items-center justify-between h-16 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-aurora-gradient shadow-glow-violet flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-base font-bold text-ink-900 dark:text-white tracking-tight truncate">DukanIQ</span>
        </a>

        <button @click="toggleTheme()"
            class="p-2 rounded-xl text-ink-500 dark:text-ink-400 hover:bg-ink-100/70 dark:hover:bg-ink-800/60 transition-all duration-200"
            aria-label="Toggle Theme">
            <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>
</header>

<nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/80 dark:bg-ink-900/75 backdrop-blur-2xl border-t border-ink-200/70 dark:border-ink-800/70 safe-bottom">
    <div class="grid grid-cols-5">
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['route']) || request()->routeIs(str($item['route'])->before('.') . '.*'); @endphp
            <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center gap-1 py-2.5">
                <svg class="w-5 h-5 transition-colors {{ $active ? 'text-aurora-violet dark:text-fuchsia-400' : 'text-ink-400 dark:text-ink-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$item['icon']] }}" />
                </svg>
                <span class="text-[10px] font-semibold {{ $active ? 'text-aurora-violet dark:text-fuchsia-400' : 'text-ink-400 dark:text-ink-500' }}">
                    {{ $item['label'] }}
                </span>
                <span class="w-1 h-1 rounded-full {{ $active ? 'bg-aurora-gradient' : 'bg-transparent' }}"></span>
            </a>
        @endforeach
    </div>
</nav>
