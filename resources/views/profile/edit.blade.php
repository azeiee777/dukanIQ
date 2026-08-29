<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl sm:text-3xl font-bold text-ink-900 dark:text-white tracking-tight mb-8">Profile</h1>

        <div class="space-y-6">
            <div class="p-6 sm:p-8 bg-white/70 dark:bg-ink-900/50 glass-panel rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-6 sm:p-8 bg-white/70 dark:bg-ink-900/50 glass-panel rounded-3xl border border-ink-200/70 dark:border-ink-800/70 shadow-glass">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-6 sm:p-8 bg-white/70 dark:bg-ink-900/50 glass-panel rounded-3xl border border-rose-200/70 dark:border-rose-500/20 shadow-glass">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
