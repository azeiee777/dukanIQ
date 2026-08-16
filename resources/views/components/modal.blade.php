@props(['name', 'show' => false, 'maxWidth' => 'md'])

@php
$id = $name ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{ show: @js($show) }"
    x-on:open-modal.window="$event.detail == '{{ $id }}' ? show = true : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:close-modal.window="$event.detail == '{{ $id }}' ? show = false : null"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[70]"
    style="display: none;"
>
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm"
        x-on:click="show = false"
    ></div>

    <div class="fixed inset-0 overflow-y-auto flex items-end sm:items-center justify-center">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95"
            class="relative w-full {{ $maxWidth }} sm:mb-6 mx-auto bg-white dark:bg-stone-900 rounded-t-3xl sm:rounded-3xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden max-h-[92vh] overflow-y-auto"
        >
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1.5 rounded-full bg-stone-300 dark:bg-stone-700"></div>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
