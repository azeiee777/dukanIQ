@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold text-ink-500 dark:text-ink-400 uppercase tracking-wide mb-1.5 ml-1']) }}>
    {{ $value ?? $slot }}
</label>
