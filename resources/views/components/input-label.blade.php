@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5 ml-1']) }}>
    {{ $value ?? $slot }}
</label>
