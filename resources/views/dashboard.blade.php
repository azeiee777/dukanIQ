<x-app-layout>
    @php
        $expenseCategories = ['Rent', 'Inventory', 'Utilities', 'Salary', 'Other'];
        $defaultFormDate = now()->format('Y-m-d');
        $editingTransaction = null;

        if (old('form_mode') === 'edit' && old('transaction_id')) {
            $editingTransaction = $transactions->firstWhere('id', (int) old('transaction_id'));
        }

        $initialForm = [
            'mode' => old('form_mode', 'create'),
            'id' => old('transaction_id'),
            'type' => old('type', $editingTransaction?->type ?? 'sale'),
            'amount' => old('amount', $editingTransaction ? (string) $editingTransaction->amount : ''),
            'date' => old('date', $editingTransaction?->date?->format('Y-m-d') ?? $defaultFormDate),
            'description' => old('description', $editingTransaction?->description ?? ''),
            'category' => old('category', $editingTransaction?->category ?? 'Sales'),
        ];

        if (($initialForm['type'] ?? 'sale') === 'sale') {
            $initialForm['category'] = 'Sales';
        } elseif (!in_array($initialForm['category'], $expenseCategories, true)) {
            $initialForm['category'] = $expenseCategories[0];
        }
    @endphp

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .dashboard-entry-dock {
            position: fixed;
            right: 1rem;
            bottom: 1.25rem;
            z-index: 70;
            pointer-events: none;
        }

        .dashboard-entry-button {
            pointer-events: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            border: 1px solid rgba(129, 140, 248, 0.3);
            border-radius: 9999px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 52%, #7c3aed 100%);
            color: #fff;
            box-shadow: 0 22px 40px -20px rgba(79, 70, 229, 0.8);
            transition: transform 0.22s ease, box-shadow 0.22s ease, filter 0.22s ease;
        }

        .dashboard-entry-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 26px 48px -22px rgba(79, 70, 229, 0.92);
            filter: brightness(1.03);
        }

        .dashboard-entry-button:focus-visible {
            outline: 0;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2), 0 26px 48px -22px rgba(79, 70, 229, 0.92);
        }

        .dashboard-entry-icon-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .dashboard-entry-label {
            display: none;
            white-space: nowrap;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        @media (min-width: 640px) {
            .dashboard-entry-dock {
                right: 1.5rem;
                bottom: 1.5rem;
            }

            .dashboard-entry-button {
                width: auto;
                height: 3.75rem;
                padding: 0 1.5rem 0 1rem;
                gap: 0.75rem;
                border-radius: 1.25rem;
            }

            .dashboard-entry-icon-wrap {
                width: 2.5rem;
                height: 2.5rem;
                border-radius: 0.95rem;
                background: rgba(255, 255, 255, 0.14);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }

            .dashboard-entry-label {
                display: inline;
            }
        }
    </style>

    <script>
        function transactionDashboard(config) {
            return {
                showModal: config.openModal,
                showDateFilters: config.showDateFilters,
                createUrl: config.createUrl,
                transactionBaseUrl: config.transactionBaseUrl,
                expenseCategories: config.expenseCategories,
                defaultDate: config.defaultDate,
                form: { ...config.defaultForm },
                init() {
                    this.syncCategoryWithType();
                },
                openCreateModal() {
                    this.form = {
                        mode: 'create',
                        id: null,
                        type: 'sale',
                        amount: '',
                        date: this.defaultDate,
                        description: '',
                        category: 'Sales',
                    };

                    this.showModal = true;
                },
                openEditModal(transaction) {
                    this.form = {
                        mode: 'edit',
                        id: transaction.id,
                        type: transaction.type,
                        amount: transaction.amount,
                        date: transaction.date,
                        description: transaction.description,
                        category: transaction.category || (transaction.type === 'expense' ? this.expenseCategories[0] : 'Sales'),
                    };

                    this.syncCategoryWithType();
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
                syncCategoryWithType() {
                    if (this.form.type === 'sale') {
                        this.form.category = 'Sales';
                        return;
                    }

                    if (!this.expenseCategories.includes(this.form.category)) {
                        this.form.category = this.expenseCategories[0];
                    }
                },
                formAction() {
                    return this.form.mode === 'edit' && this.form.id
                        ? `${this.transactionBaseUrl}/${this.form.id}`
                        : this.createUrl;
                },
                modalTitle() {
                    return this.form.mode === 'edit' ? 'Edit Entry' : 'Add Entry';
                },
                submitLabel() {
                    return this.form.mode === 'edit' ? 'Update Transaction' : 'Save Transaction';
                },
            };
        }
    </script>

    <div x-data="transactionDashboard({
        openModal: {{ $errors->any() ? 'true' : 'false' }},
        showDateFilters: {{ request('start_date') ? 'true' : 'false' }},
        createUrl: {{ \Illuminate\Support\Js::from(route('transactions.store')) }},
        transactionBaseUrl: {{ \Illuminate\Support\Js::from(url('/transactions')) }},
        expenseCategories: {{ \Illuminate\Support\Js::from($expenseCategories) }},
        defaultDate: {{ \Illuminate\Support\Js::from($defaultFormDate) }},
        defaultForm: {{ \Illuminate\Support\Js::from($initialForm) }}
    })" x-init="init()" x-on:keydown.escape.window="closeModal()" class="relative min-h-screen">

        <div class="pb-32 animate-fade-in">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Dashboard</h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>
                            @if (request('start_date') && request('end_date'))
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('d M, Y') }} -
                                {{ \Carbon\Carbon::parse(request('end_date'))->format('d M, Y') }}
                            @else
                                {{ $currentFilters['date'] == 'today' ? 'Today' : ($currentFilters['date'] == 'month' ? 'This Month' : 'All Time') }}
                            @endif
                        </span>
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 p-1.5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 inline-flex flex-wrap gap-1">
                    @foreach (['all' => 'All Time', 'month' => 'This Month', 'today' => 'Today'] as $key => $label)
                        <a href="{{ route('dashboard', ['date' => $key, 'type' => request('type'), 'category' => request('category')]) }}"
                            wire:navigate
                            class="px-4 py-2 text-xs font-bold rounded-xl transition-all duration-200 
                        {{ $currentFilters['date'] === $key && !request('start_date')
                            ? 'bg-slate-900 dark:bg-indigo-600 text-white shadow-md'
                            : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach

                    <button @click="showDateFilters = !showDateFilters"
                        :class="showDateFilters ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' :
                            'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Range
                    </button>
                </div>
            </div>

            <div x-show="showDateFilters" style="display: none;"
                class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm mb-8 mt-2 animate-fade-in">
                <form method="GET" action="{{ route('dashboard') }}"
                    class="flex flex-col sm:flex-row gap-4 items-end">
                    <input type="hidden" name="type" value="{{ request('type', 'all') }}">
                    <input type="hidden" name="category" value="{{ request('category', 'all') }}">

                    <div class="flex-1 w-full">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Start
                            Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>

                    <div class="flex-1 w-full">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">End
                            Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>

                    <div class="flex gap-2 w-full sm:w-auto">
                        <button type="submit"
                            class="flex-1 sm:flex-none px-6 py-2.5 text-sm font-bold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                            Apply
                        </button>
                        @if (request('start_date') || request('end_date'))
                            <a href="{{ route('dashboard', ['type' => request('type'), 'category' => request('category')]) }}"
                                wire:navigate
                                class="px-4 py-2.5 text-sm font-bold rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div
                            class="bg-emerald-50 dark:bg-emerald-900/20 p-3.5 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">
                            Total Sales</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-900 dark:text-white">
                            ₹{{ number_format($stats['income']) }}</h3>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div
                            class="bg-rose-50 dark:bg-rose-900/20 p-3.5 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">
                            Total Expenses</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-900 dark:text-white">
                            ₹{{ number_format($stats['expense']) }}</h3>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-violet-700 p-6 rounded-[2rem] shadow-xl text-white group">
                    <div
                        class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-2xl group-hover:bg-white/20 transition-all duration-500">
                    </div>
                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <div class="bg-white/20 p-3.5 rounded-2xl backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/10">Net
                            Profit</span>
                    </div>
                    <div class="relative z-10">
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Profit / Loss</p>
                        <h3
                            class="text-3xl lg:text-4xl font-black {{ $stats['profit'] >= 0 ? 'text-white' : 'text-rose-200' }}">
                            {{ $stats['profit'] >= 0 ? '+' : '' }}₹{{ number_format($stats['profit']) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 whitespace-nowrap">
                    Transactions
                    <span
                        class="text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">{{ count($transactions) }}</span>
                </h2>

                <div class="w-full md:w-auto overflow-x-auto no-scrollbar pb-1">
                    <div class="flex items-center gap-2 min-w-max">
                        <div
                            class="bg-white dark:bg-slate-800 p-1 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm inline-flex">
                            <a href="{{ route('dashboard', array_merge(request()->except('type'), ['type' => 'all'])) }}"
                                wire:navigate
                                class="px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all {{ $currentFilters['type'] === 'all' ? 'bg-slate-900 dark:bg-indigo-600 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                All
                            </a>
                            <a href="{{ route('dashboard', array_merge(request()->except('type'), ['type' => 'sale'])) }}"
                                wire:navigate
                                class="px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all {{ $currentFilters['type'] === 'sale' ? 'bg-emerald-500 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                Income
                            </a>
                            <a href="{{ route('dashboard', array_merge(request()->except('type'), ['type' => 'expense'])) }}"
                                wire:navigate
                                class="px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all {{ $currentFilters['type'] === 'expense' ? 'bg-rose-500 text-white shadow' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                Expense
                            </a>
                        </div>

                        @if ($currentFilters['type'] === 'expense')
                            <div class="relative">
                                <select
                                    onchange="window.location.href = '{{ route('dashboard') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), category: this.value})"
                                    class="appearance-none pl-4 pr-10 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:border-indigo-500 focus:ring-0 shadow-sm cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors h-[42px]">
                                    <option value="all"
                                        {{ request('category', 'all') === 'all' ? 'selected' : '' }}>All Categories
                                    </option>
                                    <option value="Rent" {{ request('category') === 'Rent' ? 'selected' : '' }}>Rent
                                    </option>
                                    <option value="Inventory"
                                        {{ request('category') === 'Inventory' ? 'selected' : '' }}>Inventory</option>
                                    <option value="Utilities"
                                        {{ request('category') === 'Utilities' ? 'selected' : '' }}>Utilities</option>
                                    <option value="Salary" {{ request('category') === 'Salary' ? 'selected' : '' }}>
                                        Salary</option>
                                    <option value="Other" {{ request('category') === 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('transactions.export', request()->all()) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm transition-all h-[42px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @php $currentDate = null; @endphp
                @forelse($transactions as $t)
                    @php
                        $dateObj = \Carbon\Carbon::parse($t->date);
                        $dateStr = $dateObj->format('Y-m-d');
                    @endphp

                    @if ($currentDate !== $dateStr)
                        @php $currentDate = $dateStr; @endphp
                        <div class="flex items-center gap-4 pt-2">
                            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
                            <span
                                class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                {{ $dateObj->isToday() ? 'Today' : ($dateObj->isYesterday() ? 'Yesterday' : $dateObj->format('M d, Y')) }}
                            </span>
                            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
                        </div>
                    @endif

                    <div
                        class="group bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors
                                {{ $t->type == 'sale'
                                    ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/30'
                                    : 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/30' }}">
                                @if ($t->type == 'sale')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <p class="font-bold text-slate-900 dark:text-white text-sm truncate">
                                    {{ $t->description }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                                        {{ $t->category }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right flex items-center gap-4">
                            <span
                                class="block font-black text-lg {{ $t->type == 'sale' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white' }}">
                                {{ $t->type == 'sale' ? '+' : '-' }}₹{{ number_format($t->amount) }}
                            </span>

                            <div
                                class="flex items-center gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                <button type="button"
                                    @click="openEditModal({{ \Illuminate\Support\Js::from([
                                        'id' => $t->id,
                                        'type' => $t->type,
                                        'amount' => (string) $t->amount,
                                        'date' => $dateObj->format('Y-m-d'),
                                        'description' => $t->description,
                                        'category' => $t->category,
                                    ]) }})"
                                    class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all"
                                    title="Edit entry" aria-label="Edit entry">
                                    <span class="sr-only">Edit entry</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>

                                <form action="{{ route('transactions.destroy', $t->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-all"
                                        title="Delete entry" aria-label="Delete entry">
                                        <span class="sr-only">Delete entry</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div
                            class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No transactions found</h3>
                        <p class="text-slate-500 dark:text-slate-400">Try adjusting your filters or add a new
                            transaction.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-entry-dock">
            <button @click="openCreateModal()" aria-label="New Entry" class="dashboard-entry-button" type="button">
                <span class="dashboard-entry-icon-wrap">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m7-7H5"></path>
                    </svg>
                </span>
                <span class="dashboard-entry-label">New Entry</span>
            </button>
        </div>

        <div x-show="showModal" style="display: none;" class="relative z-[60]">
            <div x-show="showModal" x-transition.opacity @click="closeModal()"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            <div class="fixed inset-0 z-[60] overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                        class="w-full max-w-md bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">

                        <div
                            class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="text-xl font-black text-slate-900 dark:text-white" x-text="modalTitle()"></h3>
                            <button @click="closeModal()"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if ($errors->any())
                            <div
                                class="mx-8 mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-200">
                                <p class="font-bold">Please fix the highlighted details and try again.</p>
                                <ul class="mt-2 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form x-bind:action="formAction()" method="POST" class="p-8 space-y-6">
                            @csrf
                            <template x-if="form.mode === 'edit'">
                                <input type="hidden" name="_method" value="PUT">
                            </template>
                            <input type="hidden" name="form_mode" :value="form.mode">
                            <input type="hidden" name="transaction_id" :value="form.id ?? ''">
                            <input type="hidden" name="type" x-model="form.type">
                            <input type="hidden" name="category" :value="form.type === 'sale' ? 'Sales' : form.category">

                            <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl">
                                <button type="button" @click="form.type = 'sale'; syncCategoryWithType()"
                                    :class="form.type === 'sale' ?
                                        'bg-white dark:bg-slate-700 text-emerald-600 shadow-sm' :
                                        'text-slate-500 dark:text-slate-400 hover:text-slate-700'"
                                    class="py-3 text-sm font-bold rounded-xl transition-all duration-200">
                                    Income
                                </button>
                                <button type="button" @click="form.type = 'expense'; syncCategoryWithType()"
                                    :class="form.type === 'expense' ?
                                        'bg-white dark:bg-slate-700 text-rose-600 shadow-sm' :
                                        'text-slate-500 dark:text-slate-400 hover:text-slate-700'"
                                    class="py-3 text-sm font-bold rounded-xl transition-all duration-200">
                                    Expense
                                </button>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Amount</label>
                                <div class="relative group">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-lg group-focus-within:text-indigo-500 transition-colors">₹</span>
                                    <input type="number" name="amount" x-model="form.amount" required step="0.01"
                                        placeholder="0.00"
                                        class="w-full pl-10 pr-4 py-4 text-2xl font-black rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-0 transition-all placeholder-slate-300">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Date</label>
                                    <input type="date" name="date" x-model="form.date" required
                                        class="w-full px-4 py-3 font-bold rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-0 transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Description</label>
                                    <input type="text" name="description" x-model="form.description" required
                                        placeholder="What was this for?"
                                        class="w-full px-4 py-3 font-bold rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-0 transition-all placeholder-slate-400">
                                </div>
                            </div>

                            <div x-show="form.type === 'expense'" x-transition>
                                <label
                                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Category</label>
                                <select x-model="form.category"
                                    class="w-full px-4 py-3 font-bold rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-0 transition-all">
                                    @foreach ($expenseCategories as $expenseCategory)
                                        <option value="{{ $expenseCategory }}">{{ $expenseCategory }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full py-4 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform active:scale-[0.98]">
                                    <span x-text="submitLabel()"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </div>
</x-app-layout>
