@extends('backend.layouts.app')

    @php
     

        $normalizeArrayInput = function ($value) {
            if (is_string($value)) {
                $value = explode(',', $value);
            }

            if (!is_array($value)) {
                return [''];
            }

            $value = array_values(
                array_filter(
                    array_map(static fn($item) => is_string($item) ? trim($item) : $item, $value),
                    static fn($item) => filled($item),
                ),
            );

            return $value ?: [''];
        };

        $languages = $normalizeArrayInput(old('languages', $employee?->languages ?? []));
        $expertise = $normalizeArrayInput(old('expertise', $employee?->expertise ?? []));
    @endphp

@section('content')
    <div class="mx-auto max-w-6xl space-y-8 pb-16">

        {{-- Flash / validation feedback --}}
        @if (session('success') || session('error') || $errors->any())
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition
                class="fixed right-5 top-3 z-[99999] w-full max-w-sm space-y-3">
                @if (session('success'))
                    <div class="relative">
                        <button @click="show = false" aria-label="Dismiss"
                            class="absolute right-3 top-3 z-10 text-neutral-500 hover:text-neutral-700">✕</button>
                        <x-ui.alert variant="success" title="" message="{{ session('success') }}" />
                    </div>
                @endif

                @if (session('error'))
                    <div class="relative">
                        <button @click="show = false" aria-label="Dismiss"
                            class="absolute right-3 top-3 z-10 text-neutral-500 hover:text-neutral-700">✕</button>
                        <x-ui.alert variant="error" title="" message="{{ session('error') }}" />
                    </div>
                @endif

                @if ($errors->any())
                    <div class="relative">
                        <button @click="show = false" aria-label="Dismiss"
                            class="absolute right-3 top-3 z-10 text-neutral-500 hover:text-neutral-700">✕</button>
                        <x-ui.alert variant="error" title="Please check the highlighted fields"
                            message="{{ $errors->first() }}" />
                    </div>
                @endif
            </div>
        @endif


        {{-- Hero Banner / Header Card --}}
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

                        @if ($employee->avatar ?? false)
                            <img src="{{ Storage::url($employee->avatar) }}" alt="{{ $employee->first_name }}"
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
        </div>

        {{-- Sticky Section Navigation Tabs --}}
        <div
            class="sticky top-19 z-50 backdrop-blur-md bg-white/80 dark:bg-neutral-900/80 p-1.5 rounded-xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <nav class="flex flex-wrap gap-1.5" x-data="{ activeTab: 'profile' }">
                {{-- profile --}}
                <a href="#profile" @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:user"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'profile' ? 'text-white' : ''"></iconify-icon>
                    <span>Profile</span>
                </a>

                 {{-- Personal --}}
                <a href="#personal" @click="activeTab = 'personal'"
                    :class="activeTab === 'personal' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:lock"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'personal' ? 'text-white' : ''"></iconify-icon>
                    <span>Personal</span>
                </a>

                {{-- Address --}}
                <a href="#address" @click="activeTab = 'address'"
                    :class="activeTab === 'address' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:map-pin"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'address' ? 'text-white' : ''"></iconify-icon>
                    <span>Address</span>
                </a>

                {{-- Bank Accounts --}}
                <a href="#bank" @click="activeTab = 'bank'"
                    :class="activeTab === 'bank' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:landmark"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'bank' ? 'text-white' : ''"></iconify-icon>
                    <span>Bank Accounts</span>
                </a>

                {{-- Documents --}}
                <a href="#documents" @click="activeTab = 'documents'"
                    :class="activeTab === 'documents' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:file-text"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'documents' ? 'text-white' : ''"></iconify-icon>
                    <span>Documents</span>
                </a>
            </nav>
        </div>

        {{-- Main Sections --}}
        <div class="space-y-8">
             <section id="profile" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.profile-update')
            </section>
            <section id="personal" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.personal')
            </section>
            <section id="address" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.address')
            </section>

            <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/50 dark:shadow-none dark:hover:shadow-lg dark:hover:shadow-black/20">
                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                <svg class="h-4 w-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                                    Employee Information
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Update your professional profile information.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8 p-6">

                        {{-- Designation / Experience --}}
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <x-form.input-text name="experience_years" label="Experience (Years)" type="number"
                                min="0" value=""
                                placeholder="0" />
                             <x-form.multi-select name="destination[]" label="Work Destinations" />
                        </div>

                        {{-- Bio --}}
                        <x-form.textarea-input name="bio" label="Bio" rows="5"
                            placeholder="Write something about yourself..." value="" />

                        {{-- Languages / Expertise --}}
                        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

                            {{-- Languages --}}
                            <div x-data="{
                                items: @js($languages),
                            
                                add() {
                                    this.items.push('');
                                },
                            
                                remove(index) {
                                    if (this.items.length === 1) {
                                        this.items = [''];
                                        return;
                                    }
                            
                                    this.items.splice(index, 1);
                                }
                            }" class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Languages
                                    </label>
                                    <button type="button" @click="add()"
                                        class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-brand-600 transition-colors hover:bg-brand-50 hover:text-brand-700 dark:text-brand-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-300">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Add Language
                                    </button>
                                </div>

                                <div class="space-y-2.5">
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="group flex items-center gap-2">
                                            <input type="text" name="languages[]" x-model="items[index]"
                                                placeholder="English"
                                                class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-400 transition-all duration-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500 dark:focus:ring-brand-500/20">
                                            <button type="button" @click="remove(index)"
                                                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 dark:border-slate-700 dark:text-slate-500 dark:hover:border-red-900/50 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                @error('languages')
                                    <p class="text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                                @error('languages.*')
                                    <p class="text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Expertise --}}
                            <div x-data="{
                                items: @js($expertise),
                            
                                add() {
                                    this.items.push('');
                                },
                            
                                remove(index) {
                                    if (this.items.length === 1) {
                                        this.items = [''];
                                        return;
                                    }
                            
                                    this.items.splice(index, 1);
                                }
                            }" class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Expertise
                                    </label>
                                    <button type="button" @click="add()"
                                        class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-brand-600 transition-colors hover:bg-brand-50 hover:text-brand-700 dark:text-brand-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-300">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Add Expertise
                                    </button>
                                </div>

                                <div class="space-y-2.5">
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="group flex items-center gap-2">
                                            <input type="text" name="expertise[]" x-model="items[index]"
                                                placeholder="SOP Writing"
                                                class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-400 transition-all duration-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500 dark:focus:ring-brand-500/20">
                                            <button type="button" @click="remove(index)"
                                                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 dark:border-slate-700 dark:text-slate-500 dark:hover:border-red-900/50 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                @error('expertise')
                                    <p class="text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                                @error('expertise.*')
                                    <p class="text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </section>

            <section id="bank" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.bank_accounts')
            </section>

            <section id="documents" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.employee_documents')
            </section>
        </div>
    </div>
@endsection
