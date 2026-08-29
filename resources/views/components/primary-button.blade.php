<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-sm text-white bg-aurora-gradient hover:brightness-110 shadow-glow-violet transition-all duration-200 transform active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-violet dark:focus:ring-offset-ink-900']) }}>
    {{ $slot }}
</button>
