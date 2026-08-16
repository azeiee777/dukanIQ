@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-stone-50 dark:bg-stone-800/50 border border-stone-200 dark:border-stone-700 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:focus:border-primary-400 text-stone-900 dark:text-white placeholder-stone-400 dark:placeholder-stone-500 transition-all duration-200 sm:text-sm']) }}>
