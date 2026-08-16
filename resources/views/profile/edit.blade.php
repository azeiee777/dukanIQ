<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-8">Profile</h1>

        <div class="space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 rounded-[2rem] border border-rose-200 dark:border-rose-900/40 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
