<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white tracking-tight mb-8">Profile</h1>

        <div class="space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-stone-800 rounded-3xl border border-stone-200 dark:border-stone-700 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-stone-800 rounded-3xl border border-stone-200 dark:border-stone-700 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-stone-800 rounded-3xl border border-rose-200 dark:border-rose-900/40 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
