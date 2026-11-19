{{-- ========================================================= --}}
{{-- EMPLOYEE PROFILE + EMPLOYMENT STATUS --}}
{{-- ========================================================= --}}

@php
    // Employment status is HR data: only managers may change it.
    $canManageEmployment = auth()->user()?->canAny([
        'employee.status.change',
        'employee.edit',
        'employee.update',
        'employee.manage',
    ]);
@endphp

<div x-data="{
    statusModalOpen: {{ $errors->hasAny(['employment_status', 'probation_period_months', 'probation_end_date', 'resignation_date', 'last_working_day']) ? 'true' : 'false' }},

    status: @js(old('employment_status', $employee->currentEmployment?->employment_status ?? 'probation')),

    probationMonths: @js((string) old('probation_period_months', $employee->currentEmployment?->probation_period_months ?? '')),

    joiningDate: @js($employee->currentEmployment?->joining_date?->format('Y-m-d') ?? ''),

    probationEndDate: @js(old('probation_end_date', $employee->currentEmployment?->probation_end_date?->format('Y-m-d') ?? '')),

    resignationDate: @js(old('resignation_date', $employee->currentEmployment?->resignation_date?->format('Y-m-d') ?? '')),

    lastWorkingDay: @js(old('last_working_day', $employee->currentEmployment?->last_working_day?->format('Y-m-d') ?? '')),

    init() {
        if (!this.probationEndDate && this.joiningDate && this.probationMonths) {
            this.calculateProbationEndDate();
        }
    },

    calculateProbationEndDate() {
        if (!this.joiningDate || !this.probationMonths) {
            this.probationEndDate = '';
            return;
        }

        const [year, month, day] = this.joiningDate.split('-').map(Number);

        const targetMonth = month - 1 + Number(this.probationMonths);
        const targetYear = year + Math.floor(targetMonth / 12);
        const normalizedMonth = targetMonth % 12;

        const lastDay = new Date(
            Date.UTC(targetYear, normalizedMonth + 1, 0)
        ).getUTCDate();

        const date = new Date(
            Date.UTC(
                targetYear,
                normalizedMonth,
                Math.min(day, lastDay)
            )
        );

        this.probationEndDate = [
            date.getUTCFullYear(),
            String(date.getUTCMonth() + 1).padStart(2, '0'),
            String(date.getUTCDate()).padStart(2, '0')
        ].join('-');
    },

    changeStatus() {
        this.lastWorkingDay = '';
        this.resignationDate = '';
    },

    closeModal() {
        this.statusModalOpen = false;
    }
}" x-init="init()" @keydown.escape.window="closeModal()">

    {{-- ========================================================= --}}
    {{-- EMPLOYEE PROFILE CARD --}}
    {{-- ========================================================= --}}

    <div
        class="relative overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

        {{-- Banner --}}
        <div class="h-32 bg-gradient-to-r from-brand-600 via-brand-500 to-indigo-600"></div>

        <div class="relative -mt-10 flex flex-col gap-4 px-6 pb-6 pt-0 sm:flex-row sm:items-end sm:justify-between">

            {{-- Employee Information --}}
            <div class="flex items-end gap-5">

                {{-- Avatar --}}
                <div
                    class="relative flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-neutral-100 text-2xl font-bold text-neutral-700 shadow-md ring-4 ring-white dark:bg-neutral-800 dark:text-neutral-200 dark:ring-neutral-900">

                    @if ($employee->user?->avatar)
                        <img src="{{ asset($employee->user->avatar) }}" alt="{{ $employee->first_name }}"
                            class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                    @endif

                </div>

                {{-- Name & Details --}}
                <div class="mb-1 min-w-0">

                    <h1 class="text-xl font-bold text-neutral-900 dark:text-white">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </h1>

                    <div class="mt-1 flex flex-wrap items-center gap-3">

                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">
                            {{ $employee->currentEmployment?->designation?->name ?? 'No Designation' }}

                            <span class="mx-1.5">&middot;</span>

                            <span
                                class="rounded bg-neutral-100 px-2 py-0.5 font-mono text-xs text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">
                                {{ $employee->currentEmployment?->employee_code ?? 'EMP-XXX' }}
                            </span>
                        </p>

                        <span
                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                            {{ ucfirst($employee->currentEmployment?->employment_status ?? 'Active') }}
                        </span>

                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="flex items-center gap-4 text-xs text-neutral-500 dark:text-neutral-400">

                <div>
                    <span class="block font-semibold text-neutral-900 dark:text-white">
                        Branch
                    </span>

                    <span>
                        {{ $employee->currentEmployment?->branch?->name ?? 'N/A' }}
                    </span>
                </div>

                <div class="border-l border-neutral-200 pl-4 dark:border-neutral-800">
                    <span class="block font-semibold text-neutral-900 dark:text-white">
                        Department
                    </span>

                    <span>
                        {{ $employee->currentEmployment?->department?->name ?? 'N/A' }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Employment Status Button (managers only) --}}
        @if ($canManageEmployment)
            <div class="absolute right-4 top-4">

                <button type="button" @click="statusModalOpen = true"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-brand-600 shadow-sm transition duration-200 hover:bg-neutral-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-white/50">
                    <iconify-icon icon="lucide:edit" class="text-base"></iconify-icon>
                    <span>Employment Status</span>
                </button>

            </div>
        @endif
    </div>


    {{-- ========================================================= --}}
    {{-- EMPLOYMENT STATUS MODAL (managers only) --}}
    {{-- ========================================================= --}}

    @if ($canManageEmployment)
    <div x-cloak x-show="statusModalOpen" x-transition:enter="transition-opacity duration-300 ease-out"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200 ease-in" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto bg-neutral-950/60 p-4 backdrop-blur-sm"
        @click.self="closeModal()">

        {{-- Modal Content --}}
        <div x-show="statusModalOpen" x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="translate-y-3 scale-[0.98] opacity-0"
            x-transition:enter-end="translate-y-0 scale-100 opacity-100"
            x-transition:leave="transition-all duration-200 ease-in"
            x-transition:leave-start="translate-y-0 scale-100 opacity-100"
            x-transition:leave-end="translate-y-2 scale-[0.98] opacity-0"
            class="my-auto w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-neutral-900">

            {{-- ================================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ================================================= --}}

            <div
                class="flex items-center justify-between border-b border-neutral-200 px-6 py-5 dark:border-neutral-800">

                <div class="flex items-center gap-3">

                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600/10 text-brand-600 dark:text-brand-400">
                        <iconify-icon icon="lucide:briefcase-business" class="text-xl"></iconify-icon>
                    </span>

                    <div>
                        <h2 class="text-lg font-bold text-neutral-900 dark:text-white">
                            Update Employment Status
                        </h2>

                        <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                            · {{ $employee->currentEmployment?->employee_code }}
                        </p>
                    </div>

                </div>

                <button type="button" @click="closeModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-neutral-500 transition duration-200 hover:bg-neutral-100 hover:text-neutral-900 dark:hover:bg-neutral-800 dark:hover:text-white">
                    <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
                </button>

            </div>


            {{-- ================================================= --}}
            {{-- FORM --}}
            {{-- ================================================= --}}

            <form action="{{ role_route('role.update.employment-status', ['employee' => $employee->id]) }}" method="POST">

                @csrf
                @method('PUT')

                @if ($errors->hasAny(['employment_status', 'probation_period_months', 'probation_end_date', 'resignation_date', 'last_working_day']))
                    <div class="mx-6 mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
                        <p class="mb-1 font-semibold">Please fix the following:</p>
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach (['employment_status', 'probation_period_months', 'probation_end_date', 'resignation_date', 'last_working_day'] as $field)
                                @foreach ($errors->get($field) as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">

                    {{-- Employment Status --}}
                    <div>

                        <label for="modal_employment_status"
                            class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                            Employment Status
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <select id="modal_employment_status" name="employment_status" x-model="status"
                                @change="changeStatus()" required
                                class="w-full appearance-none rounded-xl border border-neutral-200 bg-white px-4 py-3 pr-10 text-sm font-medium text-neutral-800 outline-none transition-all duration-200 hover:border-neutral-300 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                <option value="probation">Probation</option>
                                <option value="permanent">Permanent</option>
                                <option value="resigned">Resigned</option>
                                <option value="terminated">Terminated</option>
                            </select>

                        </div>

                        @error('employment_status')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- STATUS DETAILS --}}
                    {{-- ================================================= --}}

                    <div class="grid">

                        {{-- PROBATION --}}
                        <div x-cloak x-show="status === 'probation'"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition-all duration-200 ease-in"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="col-start-1 row-start-1 space-y-4 rounded-xl border border-amber-200 bg-amber-50/50 p-5 dark:border-amber-500/20 dark:bg-amber-500/5">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                    <iconify-icon icon="lucide:clock-3" class="text-lg"></iconify-icon>
                                </span>

                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        Probation Period
                                    </h3>

                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Set probation duration and completion date.
                                    </p>
                                </div>

                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <div>
                                    <label for="modal_probation_months"
                                        class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        Probation Period
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select id="modal_probation_months" name="probation_period_months"
                                        x-model="probationMonths" @change="calculateProbationEndDate()"
                                        :required="status === 'probation'" :disabled="status !== 'probation'"
                                        class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 disabled:cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                        <option value="">Select Duration</option>
                                        <option value="3">3 Months</option>
                                        <option value="4">4 Months</option>
                                        <option value="5">5 Months</option>
                                        <option value="6">6 Months</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="modal_probation_end_date"
                                        class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        Probation End Date
                                    </label>

                                    <input type="date" id="modal_probation_end_date" name="probation_end_date"
                                        x-model="probationEndDate" readonly :required="status === 'probation'"
                                        :disabled="status !== 'probation'"
                                        class="w-full rounded-xl border border-neutral-200 bg-neutral-100 px-4 py-3 text-sm text-neutral-700 outline-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                </div>

                            </div>

                            <p class="flex items-center gap-1.5 text-xs text-amber-800 dark:text-amber-400">
                                <iconify-icon icon="lucide:info"></iconify-icon>
                                The end date is calculated from the employee's joining date.
                            </p>

                        </div>


                        {{-- PERMANENT --}}
                        <div x-cloak x-show="status === 'permanent'"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition-all duration-200 ease-in"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="col-start-1 row-start-1 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50/50 p-5 dark:border-emerald-500/20 dark:bg-emerald-500/5">

                            <iconify-icon icon="lucide:badge-check"
                                class="mt-0.5 shrink-0 text-xl text-emerald-600 dark:text-emerald-400"></iconify-icon>

                            <div>
                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                    Permanent Employee
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-neutral-600 dark:text-neutral-400">
                                    No additional employment dates are required.
                                </p>
                            </div>

                        </div>


                        {{-- RESIGNED --}}
                        <div x-cloak x-show="status === 'resigned'"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition-all duration-200 ease-in"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="col-start-1 row-start-1 space-y-4 rounded-xl border border-orange-200 bg-orange-50/50 p-5 dark:border-orange-500/20 dark:bg-orange-500/5">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">
                                    <iconify-icon icon="lucide:log-out" class="text-lg"></iconify-icon>
                                </span>

                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        Resignation Details
                                    </h3>

                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Provide resignation and final working dates.
                                    </p>
                                </div>

                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <div>
                                    <label for="modal_resignation_date"
                                        class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        Resignation Date
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="date" id="modal_resignation_date" name="resignation_date"
                                        x-model="resignationDate" :required="status === 'resigned'"
                                        :disabled="status !== 'resigned'"
                                        class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 disabled:cursor-not-allowed disabled:opacity-60 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                </div>

                                <div>
                                    <label for="modal_resigned_last_working_day"
                                        class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        Last Working Day
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="date" id="modal_resigned_last_working_day"
                                        name="last_working_day" x-model="lastWorkingDay"
                                        :required="status === 'resigned'" :disabled="status !== 'resigned'"
                                        class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 disabled:cursor-not-allowed disabled:opacity-60 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                </div>

                            </div>

                        </div>


                        {{-- TERMINATED --}}
                        <div x-cloak x-show="status === 'terminated'"
                            x-transition:enter="transition-all duration-300 ease-out"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition-all duration-200 ease-in"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="col-start-1 row-start-1 space-y-4 rounded-xl border border-red-200 bg-red-50/50 p-5 dark:border-red-500/20 dark:bg-red-500/5">

                            <div class="flex items-center gap-3">

                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                    <iconify-icon icon="lucide:alert-circle" class="text-lg"></iconify-icon>
                                </span>

                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        Termination Details
                                    </h3>

                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        Provide the employee's final working date.
                                    </p>
                                </div>

                            </div>

                            <div>
                                <label for="modal_terminated_last_working_day"
                                    class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Last Working Day
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="date" id="modal_terminated_last_working_day" name="last_working_day"
                                    x-model="lastWorkingDay" :required="status === 'terminated'"
                                    :disabled="status !== 'terminated'"
                                    class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 disabled:cursor-not-allowed disabled:opacity-60 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white sm:max-w-md">
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MODAL FOOTER --}}
                {{-- ================================================= --}}

                <div
                    class="flex flex-col-reverse gap-3 border-t border-neutral-200 bg-neutral-50/70 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-800/40 sm:flex-row sm:justify-end">

                    <button type="button" @click="closeModal()"
                        class="rounded-xl border border-neutral-300 px-5 py-2.5 text-sm font-semibold text-neutral-700 transition duration-200 hover:bg-neutral-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-700">
                        Cancel
                    </button>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
                        <iconify-icon icon="lucide:save" class="text-base"></iconify-icon>
                        Update Employment Status
                    </button>

                </div>

            </form>

        </div>

    </div>
    @endif

</div>
