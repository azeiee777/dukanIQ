<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm text-ink-700 dark:text-ink-200 bg-white/60 dark:bg-ink-800/60 backdrop-blur-xl border border-ink-200 dark:border-ink-700 hover:border-aurora-violet/40 hover:bg-white dark:hover:bg-ink-750 transition-all duration-200 transform active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-violet dark:focus:ring-offset-ink-900']) }}>
    {{ $slot }}
</button>
