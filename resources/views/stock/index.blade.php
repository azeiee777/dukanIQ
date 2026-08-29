<x-app-layout>
    <div class="pb-4">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-ink-900 dark:text-white tracking-tight mb-2">Stock</h1>
                <p class="text-ink-500 dark:text-ink-400 font-medium">What's currently on your shelves, and what it's worth.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8">
            <div class="relative overflow-hidden bg-white/70 dark:bg-ink-900/50 glass-panel p-6 rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass">
                <p class="text-ink-500 dark:text-ink-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Stock Value</p>
                <h3 class="text-3xl font-bold text-ink-900 dark:text-white">₹{{ number_format($stockValue, 2) }}</h3>
            </div>
            <div class="relative overflow-hidden bg-white/70 dark:bg-ink-900/50 glass-panel p-6 rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass">
                <p class="text-ink-500 dark:text-ink-400 text-xs font-semibold uppercase tracking-wider mb-1">Low Stock Items</p>
                <h3 class="text-3xl font-bold {{ $lowStock->count() > 0 ? 'text-amber-500' : 'text-ink-900 dark:text-white' }}">{{ $lowStock->count() }}</h3>
            </div>
        </div>

        <div class="bg-white/70 dark:bg-ink-900/50 glass-panel rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass overflow-hidden">
            <div class="px-6 py-5 border-b border-ink-200/70 dark:border-ink-800/70 flex items-center justify-between">
                <h2 class="text-lg font-bold text-ink-900 dark:text-white">Products</h2>
                <span class="text-xs font-semibold bg-ink-100 dark:bg-ink-800 text-ink-500 dark:text-ink-400 px-2.5 py-1 rounded-lg">{{ $products->count() }}</span>
            </div>

            @forelse ($products as $product)
                <div class="px-6 py-4 flex items-center justify-between gap-4 border-b border-ink-100 dark:border-ink-800/50 last:border-b-0">
                    <div class="min-w-0">
                        <p class="font-semibold text-ink-900 dark:text-white truncate">{{ $product->name }}</p>
                        <p class="text-xs text-ink-500 dark:text-ink-400 mt-0.5">
                            Cost ₹{{ number_format($product->cost_price, 2) }} &middot; Sell ₹{{ number_format($product->sell_price, 2) }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold {{ $product->isLowStock() ? 'text-amber-500' : 'text-ink-900 dark:text-white' }}">{{ $product->quantity }} units</p>
                        @if ($product->isLowStock())
                            <p class="text-[11px] font-semibold text-amber-500 uppercase tracking-wide">Low Stock</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6">
                    <div class="w-16 h-16 bg-aurora-gradient-soft rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-aurora-violet dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7L12 3 4 7m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-ink-900 dark:text-white mb-2">No products yet</h3>
                    <p class="text-ink-500 dark:text-ink-400 max-w-sm mx-auto">Full add/edit tools for products and stock movements are launching in the next update.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
