@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-white dark:bg-ink-800/60 border border-ink-200 dark:border-ink-700 rounded-xl focus:ring-2 focus:ring-aurora-violet/30 focus:border-aurora-violet dark:focus:border-aurora-violet text-ink-900 dark:text-white placeholder-ink-400 dark:placeholder-ink-500 transition-all duration-200 sm:text-sm']) }}>
