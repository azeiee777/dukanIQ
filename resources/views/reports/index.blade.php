<x-app-layout>
    <div class="pb-4">
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-ink-900 dark:text-white tracking-tight mb-2">Reports</h1>
            <p class="text-ink-500 dark:text-ink-400 font-medium">Export your ledger as a professional, ready-to-share spreadsheet.</p>
        </div>

        <div class="bg-white/70 dark:bg-ink-900/50 glass-panel rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center gap-6 justify-between">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-14 h-14 rounded-2xl bg-aurora-gradient shadow-glow-violet flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-ink-900 dark:text-white">Sales &amp; Expenses Ledger</h2>
                    <p class="text-sm text-ink-500 dark:text-ink-400">Branded two-column Excel report with category subtotals and a final profit/loss calculation.</p>
                </div>
            </div>

            <a href="{{ route('transactions.export', ['date' => 'all']) }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm text-white bg-aurora-gradient hover:brightness-110 shadow-glow-violet transition-all duration-200 transform active:scale-[0.98] flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export All Time
            </a>
        </div>

        <p class="text-xs text-ink-400 dark:text-ink-500 mt-4 px-1">
            Want a specific date range or category instead? Head to the Dashboard, set your filters, then use Export there.
        </p>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 opacity-60">
            <div class="bg-white/50 dark:bg-ink-900/30 glass-panel rounded-3xl border border-dashed border-ink-300 dark:border-ink-700 p-6">
                <h3 class="font-bold text-ink-700 dark:text-ink-300 mb-1">Stock Report</h3>
                <p class="text-sm text-ink-500 dark:text-ink-400">Coming soon — inventory value and movement history.</p>
            </div>
            <div class="bg-white/50 dark:bg-ink-900/30 glass-panel rounded-3xl border border-dashed border-ink-300 dark:border-ink-700 p-6">
                <h3 class="font-bold text-ink-700 dark:text-ink-300 mb-1">Udhari Report</h3>
                <p class="text-sm text-ink-500 dark:text-ink-400">Coming soon — full receivables and payables ledger.</p>
            </div>
        </div>
    </div>
</x-app-layout>
