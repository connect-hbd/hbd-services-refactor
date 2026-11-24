@extends('backend.layouts.fullscreen-layout')

@section('content')
    <div class="relative min-h-screen overflow-y-auto bg-neutral-100 bg-cover bg-center dark:bg-neutral-950"
        style="background-image: url('{{ asset('frontend-img/reg-bg.webp') }}');">

        <!-- Background Overlay -->
        <div class="absolute inset-0 z-0 bg-black/78  dark:bg-neutral-950/80"></div>

        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                class="fixed top-3 right-5 z-99999 w-full max-w-sm">
                <div class="relative">
                    <x-ui.alert variant="success" title="" message="{{ session('message') }}" />
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="relative z-10 flex min-h-screen w-full items-center justify-center px-4 py-6 sm:px-6">
            <!-- Form Card -->
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-lg shadow-neutral-200/40 dark:border-neutral-800 dark:bg-neutral-900 dark:shadow-none sm:p-8">

                <!-- Header -->
                <div class="mb-7 text-center">
                    <div
                        class="mx-auto mb-3.5 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-500/10 text-brand-500 ring-1 ring-brand-500/20">
                        <iconify-icon icon="lucide:user-check" class="text-2xl"></iconify-icon>
                    </div>

                    <h1 class="text-lg font-bold tracking-tight text-neutral-900 dark:text-white sm:text-xl">
                        Welcome to HBD Services
                    </h1>

                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400 sm:text-sm">
                        Enter your phone number or email to check in
                    </p>
                </div>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="space-y-1">
                        @if ($errors->any())
                            <div
                                class="flex items-start gap-2.5 rounded-xl border border-red-200 bg-red-50/80 px-3.5 py-3 text-xs text-red-600 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300 sm:text-sm">
                                <iconify-icon icon="lucide:alert-circle" class="mt-0.5 shrink-0 text-base"></iconify-icon>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        @endif

                        <!-- Phone -->
                        <div>
                            <label for="phone"
                                class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Phone Number
                            </label>
                            <div class="group relative flex items-center">
                                <span
                                    class="pointer-events-none absolute left-3.5 flex items-center text-neutral-400 transition-colors group-focus-within:text-brand-500 dark:text-neutral-500">
                                    <iconify-icon icon="lucide:phone" class="text-lg"></iconify-icon>
                                </span>
                                <input type="tel" inputmode="numeric" id="phone" name="phone"
                                    value="{{ old('phone') }}" placeholder="01700000000" autofocus
                                    class="shadow-theme-xs focus:border-brand-400 focus:ring-brand-500/15 dark:focus:border-brand-700 h-11 w-full rounded-xl border border-neutral-300 bg-transparent pr-4 pl-10 text-sm text-neutral-800 placeholder:text-neutral-400 focus:ring-3 focus:outline-hidden dark:border-neutral-700 dark:bg-neutral-950/50 dark:text-white/90 dark:placeholder:text-neutral-500" />
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="relative flex items-center py-1 mt-5">
                            <div class="h-px flex-1 bg-neutral-200 dark:bg-neutral-800"></div>
                            <span
                                class="px-3 text-[11px] font-medium tracking-wider text-neutral-400 uppercase dark:text-neutral-500">
                                Or continue with email
                            </span>
                            <div class="h-px flex-1 bg-neutral-200 dark:bg-neutral-800"></div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Email Address
                            </label>
                            <div class="group relative flex items-center">
                                <span
                                    class="pointer-events-none absolute left-3.5 flex items-center text-neutral-400 transition-colors group-focus-within:text-brand-500 dark:text-neutral-500">
                                    <iconify-icon icon="lucide:mail" class="text-lg"></iconify-icon>
                                </span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    placeholder="example@gmail.com"
                                    class="shadow-theme-xs focus:border-brand-400 focus:ring-brand-500/15 dark:focus:border-brand-700 h-11 w-full rounded-xl border border-neutral-300 bg-transparent pr-4 pl-10 text-sm text-neutral-800 placeholder:text-neutral-400 focus:ring-3 focus:outline-hidden dark:border-neutral-700 dark:bg-neutral-950/50 dark:text-white/90 dark:placeholder:text-neutral-500" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8">
                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 active:scale-[0.99] flex h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-xl px-4 text-sm font-semibold text-white transition-all">
                                <span>Check In</span>
                                <iconify-icon icon="lucide:arrow-right" class="text-base"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
