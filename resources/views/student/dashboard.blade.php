@extends('student.layouts.app')

@section('content')
    {{-- Success toast — only flashed after the final wizard submit --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-8"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed bottom-4 right-4 sm:bottom-auto sm:top-5 z-[9999] max-w-sm w-[calc(100%-2rem)] sm:w-full">

            <div
                class="flex items-start gap-3 rounded-2xl border border-emerald-500/20 bg-white/90 dark:bg-neutral-900/90 backdrop-blur-md p-4 shadow-xl shadow-emerald-500/5 dark:border-emerald-500/30">
                <div
                    class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                    <iconify-icon icon="lucide:check-circle-2" class="text-lg"></iconify-icon>
                </div>

                <div class="flex-1 min-w-0 pt-0.5">
                    <p class="text-xs font-semibold text-neutral-900 dark:text-neutral-100">All set!</p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">{{ session('success') }}</p>
                </div>

                <button type="button" @click="show = false"
                    class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 transition-colors p-1 rounded-lg">
                    <iconify-icon icon="lucide:x" class="text-sm block"></iconify-icon>
                </button>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div
            class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800 dark:bg-neutral-900 sm:p-8">
            <span
                class="mb-3 inline-flex items-center gap-2 rounded-full border border-brand-200/80 bg-brand-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-brand-600 dark:border-brand-900 dark:bg-brand-950/40 dark:text-brand-300">
                <iconify-icon icon="lucide:layout-dashboard" class="text-xs"></iconify-icon>
                Student Dashboard
            </span>

            <h1 class="text-xl font-bold tracking-tight text-neutral-900 dark:text-white sm:text-2xl">
                Welcome back{{ $student?->first_name ? ', '.$student->first_name : '' }}.
            </h1>

            <p class="mt-2 max-w-xl text-sm text-neutral-500 dark:text-neutral-400">
                Your study abroad profile is up to date. You can review or update any step of your application at any time.
            </p>

            <a href="{{ route('student.study-abroad') }}"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                <iconify-icon icon="lucide:id-card" class="text-sm"></iconify-icon>
                Review my profile
            </a>
        </div>
    </main>
@endsection
