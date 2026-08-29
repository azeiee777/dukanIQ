<x-app-layout>
    <div class="pb-4">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-ink-900 dark:text-white tracking-tight mb-2">Udhari</h1>
                <p class="text-ink-500 dark:text-ink-400 font-medium">Who owes you, and who you owe.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <div class="bg-white/70 dark:bg-ink-900/50 glass-panel p-6 rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass">
                <p class="text-ink-500 dark:text-ink-400 text-xs font-semibold uppercase tracking-wider mb-1">You're Owed</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-mint-500">₹{{ number_format($totals['receivable'], 2) }}</h3>
            </div>
            <div class="bg-white/70 dark:bg-ink-900/50 glass-panel p-6 rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass">
                <p class="text-ink-500 dark:text-ink-400 text-xs font-semibold uppercase tracking-wider mb-1">You Owe</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-rose-500">₹{{ number_format($totals['payable'], 2) }}</h3>
            </div>
            <div class="relative overflow-hidden bg-aurora-gradient p-6 rounded-3xl shadow-glow-violet text-white">
                <p class="text-white/80 text-xs font-semibold uppercase tracking-wider mb-1">Net Position</p>
                <h3 class="text-2xl sm:text-3xl font-bold">{{ $totals['net'] >= 0 ? '+' : '' }}₹{{ number_format($totals['net'], 2) }}</h3>
            </div>
        </div>

        <div class="bg-white/70 dark:bg-ink-900/50 glass-panel rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass overflow-hidden">
            <div class="px-6 py-5 border-b border-ink-200/70 dark:border-ink-800/70 flex items-center justify-between">
                <h2 class="text-lg font-bold text-ink-900 dark:text-white">Parties</h2>
                <span class="text-xs font-semibold bg-ink-100 dark:bg-ink-800 text-ink-500 dark:text-ink-400 px-2.5 py-1 rounded-lg">{{ $parties->count() }}</span>
            </div>

            @forelse ($parties as $party)
                @php $balance = $party->balance(); @endphp
                <div class="px-6 py-4 flex items-center justify-between gap-4 border-b border-ink-100 dark:border-ink-800/50 last:border-b-0">
                    <div class="min-w-0">
                        <p class="font-semibold text-ink-900 dark:text-white truncate">{{ $party->name }}</p>
                        @if ($party->phone)
                            <p class="text-xs text-ink-500 dark:text-ink-400 mt-0.5">{{ $party->phone }}</p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold {{ $balance > 0 ? 'text-mint-500' : ($balance < 0 ? 'text-rose-500' : 'text-ink-400') }}">
                            {{ $balance == 0 ? 'Settled' : '₹' . number_format(abs($balance), 2) }}
                        </p>
                        @if ($balance != 0)
                            <p class="text-[11px] font-semibold text-ink-400 uppercase tracking-wide">{{ $balance > 0 ? 'Owes You' : 'You Owe' }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6">
                    <div class="w-16 h-16 bg-aurora-gradient-soft rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-aurora-violet dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12l3 3 6-6M6 8l-3 3 5 5 2-2m8-6l3 3-5 5-2-2M9 11l2-2 4 4-2 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-ink-900 dark:text-white mb-2">No udhari entries yet</h3>
                    <p class="text-ink-500 dark:text-ink-400 max-w-sm mx-auto">Full tools to add parties and record credit given/taken are launching in the next update.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
