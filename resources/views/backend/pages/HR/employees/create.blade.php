@extends('backend.layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl pb-12">
        <div
            class="sticky top-19 z-20  mb-6 rounded-2xl border-b border-neutral-200/60 bg-white/80 px-4 py-4 backdrop-blur-lg dark:border-neutral-800 dark:bg-neutral-900/80 sm:-mx-6 sm:px-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        {{ $isEdit ? 'Edit Employee' : 'Create Employee' }}</h1>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Add a new employee with personal, contact and
                        account details.</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ role_route('role.employees.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-5 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-400/30 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>

                    <button type="submit" form="employee-form"
                        class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500/40">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        {{ $isEdit ? 'Save Changes' : 'Create Employee' }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('error'))
            <div
                class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-400">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-400">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
                </svg>
                Please correct the {{ $errors->count() }} highlighted error{{ $errors->count() > 1 ? 's' : '' }} below and
                try again.
            </div>
        @endif

        <form id="employee-form"
            action="{{ $isEdit ? role_route('role.employees.update', ['employee' => $employee->id]) : role_route('role.employees.store') }}"
            method="POST" novalidate>
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <div class="space-y-6">
                {{-- Personal Information  --}}
                <section
                    class="overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <header
                        class="flex items-center gap-4 border-b border-neutral-100 bg-neutral-50/60 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-800/40">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600/10 text-brand-600 dark:text-brand-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Personal Information</h2>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Basic identification and contact
                                details.</p>
                        </div>
                    </header>

                    <div class="space-y-6 p-6">
                        {{-- Names --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <x-form.input-text name="first_name" label="First Name" required autocomplete="given-name"
                                :value="old('first_name', $employee?->first_name) ?? ''" placeholder="e.g. John" />

                            <x-form.input-text name="last_name" label="Last Name" required autocomplete="family-name"
                                :value="old('last_name', $employee?->last_name) ?? ''" placeholder="e.g. Doe" />
                        </div>

                        {{-- Contact --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                            <x-form.input-text name="email" label="Email Address" type="email" required
                                autocomplete="email" :value="old('email', $employee?->user?->email) ?? ''" placeholder="john@example.com" />

                            <x-form.input-text name="phone" label="Phone Number" type="tel" autocomplete="tel"
                                :value="old('phone', $employee?->user?->phone) ?? ''" placeholder="+1 (555) 000-0000" />

                            <x-form.input-text name="password" label="Password" type="password" required
                                placeholder="Minimum 8 characters" />
                        </div>

                        {{-- Emergency Contact --}}
                        <fieldset class="rounded-xl border border-dashed border-neutral-300 p-5 dark:border-neutral-700">
                            <legend class="sr-only">Emergency Contact</legend>
                            <div class="mb-4 flex items-center gap-2.5">
                                <svg class="h-4 w-4 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Emergency Contact
                                    </h3>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Who should we reach in case
                                        of an emergency?</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-form.input-text name="emergency_contact_name" label="Contact Name" :value="old('emergency_contact_name', $employee?->emergency_contact_name) ?? ''"
                                    placeholder="Full name" />

                                <x-form.input-text name="emergency_contact_phone" label="Phone Number" type="tel"
                                    :value="old('emergency_contact_phone', $employee?->emergency_contact_phone) ??
                                        ''" placeholder="+1 (555) 000-0000" />
                            </div>
                        </fieldset>
                    </div>
                </section>

                {{-- Employment Information --}}
                @include('backend.pages.HR.employees.partial.employeement')

                {{-- Account Settings --}}

                @php
                    $currentStatus = old('employment_status', $employment?->employment_status ?? 'probation');
                @endphp

                <section x-data="{
                    status: @js($currentStatus),
                }"
                    class="rounded-2xl border border-neutral-200/80 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

                    {{-- Header --}}
                    <header
                        class="flex items-center gap-4 border-b border-neutral-100 bg-neutral-50/60 px-6 py-5 dark:border-neutral-800 dark:bg-neutral-800/40">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-600/10 text-brand-600 dark:text-brand-400">
                            <iconify-icon icon="lucide:briefcase-business" class="text-xl"></iconify-icon>
                        </span>

                        <div>
                            <h2 class="text-base font-semibold text-neutral-900 dark:text-white">
                                Employment Settings
                            </h2>
                            <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                Manage employment status, probation and working dates.
                            </p>
                        </div>
                    </header>

                    <div class="space-y-6 p-6">
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                            {{-- Employment Status --}}
                            <div>
                                <label for="employment_status"
                                    class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    Employment Status <span class="text-red-500">*</span>
                                </label>

                                <select id="employment_status" name="employment_status" x-model="status" required
                                    class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-800 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                                    <option value="probation">Probation</option>
                                    <option value="permanent">Permanent</option>
                                    <option value="resigned">Resigned</option>
                                    <option value="terminated">Terminated</option>
                                </select>

                                @error('employment_status')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Current Salary --}}
                            <div>
                                <label for="salary_amount"
                                    class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-400">
                                    Current Salary <span class="text-red-500">*</span>
                                </label>

                                <input type="number" id="salary_amount" name="salary_amount" step="0.01" min="0"
                                    value="{{ old('salary_amount', $salaryHistory?->salary_amount) }}" required
                                    placeholder="e.g. 500000"
                                    class="h-11 w-full placeholder:text-slate-400 rounded-lg border border-neutral-300 bg-white px-4 text-sm text-neutral-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">

                                @error('salary_amount')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Probation Fields --}}
                        <div x-cloak x-show="status === 'probation'" x-transition
                            class="space-y-4 rounded-xl border border-amber-200 bg-amber-50/50 p-5 dark:border-amber-500/20 dark:bg-amber-500/5">

                            <div class="flex items-start gap-3">
                                <span
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                    <iconify-icon icon="lucide:clock-3" class="text-lg"></iconify-icon>
                                </span>

                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        Probation Period
                                    </h3>
                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                        Set the probation duration and expected completion date.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <x-form.select-input name="probation_period_months" label="Probation Period"
                                    :options="[
                                        '3' => '3 Months',
                                        '4' => '4 Months',
                                        '5' => '5 Months',
                                        '6' => '6 Months',
                                    ]" :value="old('probation_period_months', $employment?->probation_period_months) ?? ''" />
                                <div>
                                    <label for="probation_end_date"
                                        class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-400">
                                        Probation End Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="probation_end_date" name="probation_end_date"
                                        value="{{ old('probation_end_date', $employment?->probation_end_date?->format('Y-m-d')) }}"
                                        readonly required
                                        class="h-11 w-full cursor-not-allowed rounded-lg border border-neutral-300 bg-neutral-100 px-4 text-sm text-neutral-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                    @error('probation_end_date')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                            <p class="flex items-start gap-2 text-xs leading-relaxed text-amber-800 dark:text-amber-400">
                                <iconify-icon icon="lucide:info" class="mt-0.5 shrink-0"></iconify-icon>
                                The end date is calculated from the joining date and probation period.
                            </p>
                        </div>

                        {{-- Permanent Information --}}
                        <div x-cloak x-show="status === 'permanent'" x-transition
                            class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50/50 p-5 dark:border-emerald-500/20 dark:bg-emerald-500/5">

                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                <iconify-icon icon="lucide:badge-check" class="text-lg"></iconify-icon>
                            </span>

                            <div>
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    Permanent Employee
                                </h3>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    No additional employment dates are required for permanent employees.
                                </p>
                            </div>
                        </div>

                        {{-- Resigned Fields --}}
                        <div x-cloak x-show="status === 'resigned'" x-transition
                            class="space-y-4 rounded-xl border border-orange-200 bg-orange-50/50 p-5 dark:border-orange-500/20 dark:bg-orange-500/5">

                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">
                                    <iconify-icon icon="lucide:calendar-x" class="text-lg"></iconify-icon>
                                </span>

                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        Resignation Details
                                    </h3>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Record resignation and final working dates.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <div>
                                    <x-form.date-picker id="resignation_date" name="resignation_date" label="Resignation Date" required
                                        placeholder="Select resignation date" dateFormat="Y-m-d"
                                        x-bind:required="status === 'resigned'" x-bind:disabled="status !== 'resigned'"
                                        :defaultDate="old('resignation_date', $employment?->resignation_date?->format('Y-m-d'))" />

                                    @error('resignation_date')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <x-form.date-picker id="resigned_last_working_day" name="last_working_day" label="Last Working Day" required
                                        placeholder="Select last working day" dateFormat="Y-m-d"
                                        x-bind:required="status === 'resigned'" x-bind:disabled="status !== 'resigned'"
                                        :defaultDate="old('last_working_day', $employment?->last_working_day?->format('Y-m-d'))" />

                                    @error('last_working_day')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- Terminated Fields --}}
                        <div x-cloak x-show="status === 'terminated'" x-transition
                            class="space-y-4 rounded-xl border border-red-200 bg-red-50/50 p-5 dark:border-red-500/20 dark:bg-red-500/5">

                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                    <iconify-icon icon="lucide:calendar-x" class="text-lg"></iconify-icon>
                                </span>

                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        Termination Details
                                    </h3>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Record the employee's final working date.
                                    </p>
                                </div>
                            </div>

                            <div>
                                <x-form.date-picker id="terminated_last_working_day" name="last_working_day" label="Last Working Day" required
                                    placeholder="Select last working day" dateFormat="Y-m-d" class="sm:max-w-md"
                                    x-bind:required="status === 'terminated'" x-bind:disabled="status !== 'terminated'"
                                    :defaultDate="old('last_working_day', $employment?->last_working_day?->format('Y-m-d'))" />

                                @error('last_working_day')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                </section>


            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (() => {
                const initializeProbationEndDate = () => {
                    const joiningDate = document.getElementById('joining_date');
                    const probationMonths = document.getElementById('probation_period_months');
                    const probationEndDate = document.getElementById('probation_end_date');

                    if (!joiningDate || !probationMonths || !probationEndDate) return;

                    const calculateEndDate = (joiningValue = joiningDate.value) => {
                        if (!joiningValue || !probationMonths.value) {
                            probationEndDate.value = '';
                            return;
                        }

                        const [year, month, day] = joiningValue.split('-').map(Number);
                        const startMonth = month - 1;
                        const targetMonth = startMonth + Number(probationMonths.value);
                        const targetYear = year + Math.floor(targetMonth / 12);
                        const normalizedMonth = targetMonth % 12;
                        const lastDay = new Date(Date.UTC(targetYear, normalizedMonth + 1, 0)).getUTCDate();
                        const endDate = new Date(Date.UTC(targetYear, normalizedMonth, Math.min(day, lastDay)));

                        probationEndDate.value = [
                            endDate.getUTCFullYear(),
                            String(endDate.getUTCMonth() + 1).padStart(2, '0'),
                            String(endDate.getUTCDate()).padStart(2, '0'),
                        ].join('-');
                    };

                    joiningDate.addEventListener('input', () => calculateEndDate());
                    joiningDate.addEventListener('change', () => calculateEndDate());
                    probationMonths.addEventListener('change', () => calculateEndDate());

                    // Seed the end date from the server-rendered value so it is
                    // correct on first load (the flatpickr picker initializes later).
                    calculateEndDate(@json(old('joining_date', $employment?->joining_date?->format('Y-m-d'))));
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initializeProbationEndDate);
                } else {
                    initializeProbationEndDate();
                }
            })();
        </script>
    @endpush
@endsection
