@extends('backend.layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl pb-12">
        <div
            class="sticky top-19 z-20  mb-6 rounded-2xl border-b border-neutral-200/60 bg-white/80 px-4 py-4 backdrop-blur-lg dark:border-neutral-800 dark:bg-neutral-900/80 sm:-mx-6 sm:px-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-neutral-900 dark:text-white">Create Employee</h1>
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
                        Create Employee
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


        @endsection