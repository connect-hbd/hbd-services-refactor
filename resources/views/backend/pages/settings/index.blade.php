@extends('backend.layouts.app')

@section('title', 'System Settings')

@section('content')
   @php
    $logoPath = $companySettings->get('logo');
    $faviconPath = $companySettings->get('favicon');

    $logoUrl = $logoPath
        ? Storage::disk('public')->url($logoPath)
        : null;

    $faviconUrl = $faviconPath
        ? Storage::disk('public')->url($faviconPath)
        : null;

    $weekendDays = $attendanceSettings->get('weekend_days', []);

    $whitelistIps = $securitySettings->get('whitelist_ips', []);
@endphp

    <div x-data="settingsPage({
    initialTab: new URLSearchParams(window.location.search).get('tab') || 'company',

    submitUrl: @js(role_route('role.setting-update')),

    csrfToken: @js(csrf_token()),

    initialLogo: @js($logoUrl),

    initialFavicon: @js($faviconUrl),

    ipWhitelistEnabled: @js(
        (bool) $securitySettings->get('ip_whitelist_enabled', false)
    ),

    ips: @js(array_values($whitelistIps)),

    savedWeekendDays: @js(array_values($weekendDays)),
})" x-init="init()" class="bg-neutral-50 dark:bg-neutral-950">

        {{-- ── Scoped styling for upgraded controls ─────────────────────────── --}}
        <style>
            [x-data^="settingsPage"] .seg-tab {
                position: relative;
            }

            [x-data^="settingsPage"] .switch {
                transition: background-color .2s ease;
            }

            [x-data^="settingsPage"] .switch .knob {
                transition: transform .2s cubic-bezier(.4, 0, .2, 1);
            }

            [x-data^="settingsPage"] .dz {
                transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
            }

            [x-data^="settingsPage"] .dz:hover {
                border-color: rgb(var(--color-brand-400, 99 102 241) / .6);
            }

            [x-data^="settingsPage"] .section-card {
                transition: box-shadow .2s ease, border-color .2s ease;
            }

            [x-data^="settingsPage"] .section-card:hover {
                box-shadow: 0 4px 24px -8px rgb(0 0 0 / .08);
            }

            .dark [x-data^="settingsPage"] .section-card:hover {
                box-shadow: 0 4px 24px -8px rgb(0 0 0 / .45);
            }
        </style>

        {{-- Decorative page backdrop --}}
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-brand-500/[0.07] dark:from-brand-500/[0.10] to-transparent">
        </div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- ══════════════ Page Header ══════════════ --}}
            <div class="flex flex-col gap-4 mb-8">
                <div>
                    
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">
                        Settings
                    </h1>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1.5 ">
                        Manage organization branding, leave &amp; attendance rules, storage, and security policies.
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-xs text-neutral-400 dark:text-neutral-500">
                    <iconify-icon icon="lucide:shield-check" class="w-4 h-4"></iconify-icon>
                    Changes apply workspace-wide
                </div>
            </div>

            {{-- ══════════════ Feedback Banner ══════════════ --}}
            <div x-show="alert.message" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                :class="alert.type === 'success' ?
                    'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200' :
                    'bg-red-50 dark:bg-red-950/30 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200'"
                class="mb-6 p-4 rounded-2xl border shadow-sm flex items-start justify-between gap-4" style="display: none;"
                role="alert">
                <div class="flex items-start gap-3">
                    <template x-if="alert.type === 'success'">
                        <span
                            class="grid place-items-center w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 shrink-0">
                            <iconify-icon icon="lucide:circle-check"
                                class="w-4.5 h-4.5 text-emerald-600 dark:text-emerald-400"></iconify-icon>
                        </span>
                    </template>
                    <template x-if="alert.type === 'error'">
                        <span class="grid place-items-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 shrink-0">
                            <iconify-icon icon="lucide:circle-alert"
                                class="w-4.5 h-4.5 text-red-600 dark:text-red-400"></iconify-icon>
                        </span>
                    </template>
                    <div class="text-sm pt-1">
                        <p class="font-semibold" x-text="alert.message"></p>
                        <template x-if="alert.errors.length > 0">
                            <ul class="list-disc list-inside mt-1.5 space-y-0.5 text-xs opacity-90">
                                <template x-for="(err, idx) in alert.errors" :key="idx">
                                    <li x-text="err"></li>
                                </template>
                            </ul>
                        </template>
                    </div>
                </div>
                <button type="button" @click="alert.message = ''"
                    class="text-current opacity-60 hover:opacity-100 transition p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10"
                    aria-label="Dismiss">
                    <iconify-icon icon="lucide:x" class="w-4 h-4"></iconify-icon>
                </button>
            </div>

            {{-- ══════════════ Navigation Tabs (segmented control) ══════════════ --}}
            <div class="sticky top-4 z-20 mb-6">
                <div
                    class="bg-white/80 dark:bg-neutral-900/80 backdrop-blur-lg rounded-2xl shadow-sm border border-neutral-200/80 dark:border-neutral-800 p-1.5">
                    <nav class="flex gap-1 overflow-x-auto no-scrollbar" role="tablist" aria-label="Settings sections">
                        <template x-for="tab in tabs" :key="tab.id">
                            <button type="button" role="tab" :aria-selected="activeTab === tab.id"
                                @click="selectTab(tab.id)"
                                :class="activeTab === tab.id ?
                                    'bg-brand-600 text-white shadow-md shadow-brand-600/25' :
                                    'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-neutral-900 dark:hover:text-white'"
                                class="seg-tab px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 flex items-center gap-2 cursor-pointer whitespace-nowrap">
                                <iconify-icon :icon="tab.icon" class="text-base"></iconify-icon>
                                <span x-text="tab.label"></span>
                            </button>
                        </template>
                    </nav>
                </div>
            </div>

            {{-- ══════════════ Settings Form ══════════════ --}}
            <form x-ref="form" @submit.prevent="submitForm" enctype="multipart/form-data"
                class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-neutral-200 dark:border-neutral-800">

                @csrf

                <div class="p-6 sm:p-8 space-y-10">

                    {{-- ══════════ COMPANY TAB ══════════ --}}
                    <div x-show="activeTab === 'company'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10">

                        {{-- Brand Identity --}}
                        <section
                            class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 overflow-hidden">
                            <header
                                class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                <span
                                    class="grid place-items-center w-9 h-9 rounded-xl bg-brand-500/10 text-brand-500 dark:bg-brand-500/20 dark:text-brand-300">
                                    <iconify-icon icon="lucide:palette" class="w-4.5 h-4.5"></iconify-icon>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Brand Identity</h2>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Your company name, logo, and
                                        browser favicon.</p>
                                </div>
                            </header>

                            <div class="p-6 space-y-8">
                                <div class="max-w-md">
                                    <x-form.input-text id="company_name" name="company_name" label="Company Name"
                                        :value="$companySettings->get('company_name', '')" required placeholder="My Company" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Logo upload: dropzone style --}}
                                    <div>
                                        <span
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">Company
                                            Logo</span>
                                        <div x-show="logoPreview" x-transition
                                            class="mb-3 p-4 bg-white dark:bg-neutral-800/90 rounded-2xl border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                                            <img :src="logoPreview" alt="Company Logo"
                                                class="h-14 max-w-[180px] object-contain rounded-lg bg-neutral-50 dark:bg-neutral-900 p-2">
                                            <div class="min-w-0">
                                                <span
                                                    class="block text-[11px] font-semibold uppercase tracking-wider text-neutral-400">Preview</span>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">This is how
                                                    your logo appears in the app header and emails.</p>
                                            </div>
                                        </div>
                                        <label for="logoInput"
                                            class="dz flex flex-col items-center justify-center gap-2 px-4 py-8 rounded-2xl border-2 border-dashed border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800/60 cursor-pointer text-center">
                                            <span
                                                class="grid place-items-center w-10 h-10 rounded-full bg-brand-500/10 text-brand-500 dark:bg-brand-500/20 dark:text-brand-300">
                                                <iconify-icon icon="lucide:image-plus" class="w-5 h-5"></iconify-icon>
                                            </span>
                                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                                                <span class="text-brand-500 font-semibold">Click to upload</span> a logo
                                            </span>
                                            <span class="text-xs text-neutral-400">PNG, JPG or SVG &middot; recommended
                                                height 64px</span>
                                        </label>
                                        <input id="logoInput" type="file" name="logo" accept="image/*"
                                            @change="handleFilePreview($event, 'logo')" class="sr-only">
                                    </div>

                                    {{-- Favicon upload: dropzone style --}}
                                    <div>
                                        <span
                                            class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">Favicon</span>
                                        <div x-show="faviconPreview" x-transition
                                            class="mb-3 p-4 bg-white dark:bg-neutral-800/90 rounded-2xl border border-neutral-200 dark:border-neutral-700 flex items-center gap-4">
                                            <img :src="faviconPreview" alt="Favicon"
                                                class="h-12 w-12 object-contain rounded-xl border border-neutral-200 dark:border-neutral-700 p-1.5 bg-neutral-50 dark:bg-neutral-900">
                                            <div class="min-w-0">
                                                <span
                                                    class="block text-[11px] font-semibold uppercase tracking-wider text-neutral-400">Preview</span>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">Shown in
                                                    browser tabs and bookmarks.</p>
                                            </div>
                                        </div>
                                        <label for="faviconInput"
                                            class="dz flex flex-col items-center justify-center gap-2 px-4 py-8 rounded-2xl border-2 border-dashed border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800/60 cursor-pointer text-center">
                                            <span
                                                class="grid place-items-center w-10 h-10 rounded-full bg-brand-500/10 text-brand-500 dark:bg-brand-500/20 dark:text-brand-300">
                                                <iconify-icon icon="lucide:globe" class="w-5 h-5"></iconify-icon>
                                            </span>
                                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                                                <span class="text-brand-500 font-semibold">Click to upload</span> a favicon
                                            </span>
                                            <span class="text-xs text-neutral-400">ICO or PNG &middot; 32&times;32 or
                                                larger, square</span>
                                        </label>
                                        <input id="faviconInput" type="file" name="favicon"
                                            accept=".ico,.png,.jpg,.jpeg,.svg"
                                            @change="handleFilePreview($event, 'favicon')" class="sr-only">
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Contact Information --}}
                        <section
                            class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 overflow-hidden">
                            <header
                                class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                <span
                                    class="grid place-items-center w-9 h-9 rounded-xl bg-sky-500/10 text-sky-500 dark:bg-sky-500/20 dark:text-sky-300">
                                    <iconify-icon icon="lucide:mail" class="w-4.5 h-4.5"></iconify-icon>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Contact Information
                                    </h2>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Public contact details
                                        displayed across correspondence and portals.</p>
                                </div>
                            </header>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <x-form.textarea-input id="address" name="address" label="Address"
                                            rows="3" :value="$companySettings->get('address', '')" placeholder="Enter complete building or street address..." />
                                    </div>
                                    <div>
                                        <x-form.input-text id="phone" name="phone" label="Phone"
                                            :value="$companySettings->get('phone', '')" placeholder="+1 (555) 000-0000" />
                                    </div>
                                    <div>
                                        <x-form.input-text id="email" type="email" name="email" label="Email"
                                            :value="$companySettings->get('email', '')" placeholder="john@example.com" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-form.input-text id="website" type="url" name="website" label="Website"
                                            :value="$companySettings->get('website', '')" placeholder="https://example.com" />
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- ══════════ LEAVE & ATTENDANCE TAB ══════════ --}}
                    <div x-show="activeTab === 'leave'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10" style="display: none;">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Weekend --}}
                            <section
                                class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 ">
                                <header
                                    class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                    <span
                                        class="grid place-items-center w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20 dark:text-emerald-300">
                                        <iconify-icon icon="lucide:calendar-days" class="w-4.5 h-4.5"></iconify-icon>
                                    </span>
                                    <div>
                                        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Weekend Schedule
                                        </h2>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Select weekly off-days
                                            used for attendance and leave calculations.</p>
                                    </div>
                                </header>

                                <div class="p-6">
                                    <x-form.multi-select name="weekend_days" label="Weekend Days" :options="[
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ]" />
                                </div>
                            </section>

                            {{-- Leave Rules --}}
                            <section
                                class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 overflow-hidden">
                                <header
                                    class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                    <span
                                        class="grid place-items-center w-9 h-9 rounded-xl bg-violet-500/10 text-violet-500 dark:bg-violet-500/20 dark:text-violet-300">
                                        <iconify-icon icon="lucide:calendar-off" class="w-4.5 h-4.5"></iconify-icon>
                                    </span>
                                    <div>
                                        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Leave Rules</h2>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Configure baseline leave
                                            limits for employees.</p>
                                    </div>
                                </header>

                                <div class="p-6">
                                    <div class="">
                                        <x-form.input-text id="max_consecutive_days" type="number"
                                            name="max_consecutive_days" label="Max Consecutive Leave Days"
                                            :value="$settings['max_consecutive_days']->value ?? ''" required min="1" placeholder="14/15" />
                                    </div>
                                </div>
                            </section>

                        </div>

                        {{-- Working Hours --}}
                        <section
                            class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 overflow-hidden">
                            <header
                                class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                <span
                                    class="grid place-items-center w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 dark:bg-amber-500/20 dark:text-amber-300">
                                    <iconify-icon icon="lucide:clock-3" class="w-4.5 h-4.5"></iconify-icon>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Working Hours</h2>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Define shift timings and
                                        grace thresholds for late check-ins and early departures.</p>
                                </div>
                            </header>

                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <x-form.input-text id="working_hours_start" type="time"
                                            name="working_hours_start" label="Office Start Time" :value="$settings['working_hours_start']->value ?? ''"
                                            required placeholder="09:00" />
                                    </div>
                                    <div>
                                        <x-form.input-text id="working_hours_end" type="time" name="working_hours_end"
                                            label="Office End Time" :value="$settings['working_hours_end']->value ?? ''" required placeholder="18:00" />
                                    </div>
                                    <div>
                                        <x-form.input-text id="late_threshold_minutes" type="number"
                                            name="late_threshold_minutes" label="Late Threshold (minutes)"
                                            :value="$settings['late_threshold_minutes']->value ?? ''" required min="0" placeholder="15" />
                                    </div>
                                    <div>
                                        <x-form.input-text id="early_departure_threshold_minutes" type="number"
                                            name="early_departure_threshold_minutes"
                                            label="Early Departure Threshold (minutes)" :value="$settings['early_departure_threshold_minutes']->value ?? ''" required
                                            min="0" placeholder="15" />
                                    </div>
                                </div>
                            </div>
                        </section>


                    </div>

                    {{-- ══════════ FILE STORAGE TAB ══════════ --}}
                    <div x-show="activeTab === 'storage'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">

                        <section
                            class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 overflow-hidden">
                            <header
                                class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                <span
                                    class="grid place-items-center w-9 h-9 rounded-xl bg-cyan-500/10 text-cyan-500 dark:bg-cyan-500/20 dark:text-cyan-300">
                                    <iconify-icon icon="lucide:hard-drive" class="w-4.5 h-4.5"></iconify-icon>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">File Storage Driver
                                    </h2>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Choose where newly uploaded
                                        files are stored. Existing local files remain accessible regardless of this setting.
                                    </p>
                                </div>
                            </header>

                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl">
                                    <label @click="storageDriver = 'local'"
                                        :class="storageDriver === 'local'
                                            ?
                                            'border-brand-500 bg-brand-500/5 dark:bg-brand-500/10 ring-2 ring-brand-500/20 shadow-sm shadow-brand-500/10' :
                                            'border-neutral-200 dark:border-neutral-700 hover:border-neutral-300 dark:hover:border-neutral-600'"
                                        class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition">
                                        <input type="radio" name="storage_driver" value="local"
                                            x-model="storageDriver" id="storageDriverLocal"
                                            class="mt-0.5 w-4 h-4 text-brand-500 border-neutral-300 focus:ring-brand-500">
                                        <span
                                            class="grid place-items-center w-9 h-9 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 shrink-0">
                                            <iconify-icon icon="lucide:server" class="w-4.5 h-4.5"></iconify-icon>
                                        </span>
                                        <span>
                                            <span
                                                class="block text-sm font-semibold text-neutral-900 dark:text-white">Local
                                                Storage</span>
                                            <span class="block text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">Store
                                                files directly on the application server disk.</span>
                                        </span>
                                    </label>

                                    <label @click="storageDriver = 's3'"
                                        :class="storageDriver === 's3'
                                            ?
                                            'border-brand-500 bg-brand-500/5 dark:bg-brand-500/10 ring-2 ring-brand-500/20 shadow-sm shadow-brand-500/10' :
                                            'border-neutral-200 dark:border-neutral-700 hover:border-neutral-300 dark:hover:border-neutral-600'"
                                        class="flex items-start gap-3.5 p-4 rounded-2xl border cursor-pointer transition">
                                        <input type="radio" name="storage_driver" value="s3"
                                            x-model="storageDriver" id="storageDriverS3"
                                            class="mt-0.5 w-4 h-4 text-brand-500 border-neutral-300 focus:ring-brand-500">
                                        <span
                                            class="grid place-items-center w-9 h-9 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 shrink-0">
                                            <iconify-icon icon="lucide:cloud-upload" class="w-4.5 h-4.5"></iconify-icon>
                                        </span>
                                        <span>
                                            <span
                                                class="block text-sm font-semibold text-neutral-900 dark:text-white">Amazon
                                                S3</span>
                                            <span class="block text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">Store
                                                uploads in a configured S3 object bucket.</span>
                                        </span>
                                    </label>
                                </div>

                                <div x-show="storageDriver === 's3'" x-transition
                                    class="max-w-2xl flex items-start gap-2.5 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200">
                                    <iconify-icon icon="lucide:triangle-alert"
                                        class="w-4 h-4 shrink-0 mt-0.5"></iconify-icon>
                                    <p>Ensure your <code class="font-mono font-semibold">AWS_*</code> bucket credentials
                                        are configured in your environment before saving.</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- ══════════ SECURITY TAB ══════════ --}}
                    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;">

                        <section
                            class="section-card rounded-2xl bg-neutral-50/70 dark:bg-neutral-800/40 border border-neutral-200/70 dark:border-neutral-700/70 overflow-hidden">
                            <header
                                class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-neutral-200/60 dark:border-neutral-700/60">
                                <span
                                    class="grid place-items-center w-9 h-9 rounded-xl bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-300">
                                    <iconify-icon icon="lucide:shield-check" class="w-4.5 h-4.5"></iconify-icon>
                                </span>
                                <div>
                                    <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Security &amp; Fraud
                                        Prevention</h2>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Restrict attendance actions
                                        to approved office or network IP addresses.</p>
                                </div>
                            </header>

                            <div class="p-6 space-y-6">

                                {{-- Enable IP Whitelist: modern switch --}}
                                <div
                                    class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-neutral-900/70 border border-neutral-200/80 dark:border-neutral-700/80 flex items-center justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="grid place-items-center w-9 h-9 rounded-xl bg-brand-500/10 text-brand-500 dark:bg-brand-500/20 dark:text-brand-300 shrink-0"
                                            :class="ipWhitelistEnabled ? '' : 'opacity-50'">
                                            <iconify-icon icon="lucide:network" class="w-4.5 h-4.5"></iconify-icon>
                                        </span>
                                        <div>
                                            <label for="ip_whitelist_enabled"
                                                class="text-sm font-semibold text-neutral-900 dark:text-white cursor-pointer block">
                                                Enable IP Whitelist for Attendance
                                            </label>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">
                                                Block check-ins from IP addresses outside the list below when enabled.
                                            </p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="ip_whitelist_enabled" value="0">
                                    <label for="ip_whitelist_enabled"
                                        class="relative inline-flex cursor-pointer select-none"
                                        title="Toggle IP whitelist">
                                        <input id="ip_whitelist_enabled" type="checkbox" name="ip_whitelist_enabled"
                                            value="1" x-model="ipWhitelistEnabled" class="peer sr-only">
                                        <span
                                            class="switch block w-11 h-6 rounded-full bg-neutral-300 dark:bg-neutral-600 peer-checked:bg-brand-500 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-500/40 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-white dark:peer-focus-visible:ring-offset-neutral-900"
                                            aria-hidden="true"></span>
                                        <span
                                            class="switch knob absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow-md peer-checked:translate-x-5"
                                            aria-hidden="true"></span>
                                    </label>
                                </div>

                                {{-- Whitelisted IPs --}}
                                <div class="max-w-2xl">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-1">
                                        Whitelisted IP Addresses
                                    </label>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-4">
                                        Only allow access from these IPs. Leave empty to allow all addresses.
                                    </p>

                                    <template x-if="ips.length === 0">
                                        <div
                                            class="mb-4 px-4 py-6 rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 text-center">
                                            <iconify-icon icon="lucide:list-x"
                                                class="w-5 h-5 mx-auto text-neutral-300 dark:text-neutral-600 mb-1.5"></iconify-icon>
                                            <p class="text-xs text-neutral-400 dark:text-neutral-500 italic">No IP
                                                addresses added yet.</p>
                                        </div>
                                    </template>

                                    <div class="space-y-2.5 mb-4">
                                        <template x-for="(ip, index) in ips" :key="index">
                                            <div class="flex items-center gap-2.5 group">
                                                <span
                                                    class="grid place-items-center w-10 h-10 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-400 dark:text-neutral-500 shrink-0 border border-neutral-200/60 dark:border-neutral-700/60">
                                                    <iconify-icon icon="lucide:wifi" class="w-4 h-4"></iconify-icon>
                                                </span>
                                                <x-form.input-text type="text" name="whitelist_ips[]"
                                                    placeholder="e.g. 192.168.1.1" x-model="ips[index]" class="flex-1" />
                                                <button type="button" @click="removeIp(index)"
                                                    class="flex items-center justify-center p-2.5 w-10 h-10 rounded-lg text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-950/70 hover:text-red-600 transition cursor-pointer opacity-60 group-hover:opacity-100"
                                                    title="Remove IP">
                                                    <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" @click="addIp()"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium bg-brand-500/10 text-brand-500 dark:bg-brand-500/20 dark:text-brand-300 hover:bg-brand-500/20 transition cursor-pointer">
                                        <iconify-icon icon="lucide:plus" class="w-4 h-4"></iconify-icon>
                                        Add New IP
                                    </button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                {{-- ══════════════ Sticky Action Bar ══════════════ --}}
                <div
                    class="sticky bottom-0 z-10 px-6 sm:px-8 py-4 bg-white/85 dark:bg-neutral-900/85 backdrop-blur-lg border-t border-neutral-200/80 dark:border-neutral-800 flex items-center justify-end gap-4">

                    <button type="submit" :disabled="saving"
                        :class="saving ? 'opacity-70 cursor-not-allowed' :
                            'hover:bg-brand-600 active:scale-[0.98] shadow-lg shadow-brand-500/25'"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-brand-500 shadow-md transition cursor-pointer">
                        <iconify-icon x-show="saving" icon="lucide:loader-circle" class="animate-spin h-4 w-4"
                            style="display: none;"></iconify-icon>
                        <iconify-icon x-show="!saving" icon="lucide:save" class="h-4 w-4"></iconify-icon>
                        <span x-text="saving ? 'Saving Changes...' : 'Save Changes'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function settingsPage(config) {
            return {
                tabs: [{
                        id: 'company',
                        label: 'Company Settings',
                        icon: 'lucide:building-2'
                    },
                    {
                        id: 'leave',
                        label: 'Leave & Attendance',
                        icon: 'lucide:clock-3'
                    },
                    {
                        id: 'storage',
                        label: 'File Storage',
                        icon: 'lucide:hard-drive'
                    },
                    {
                        id: 'security',
                        label: 'Security & Fraud Prevention',
                        icon: 'lucide:shield-check'
                    },
                ],
                activeTab: config.initialTab || 'company',
                saving: false,
                logoPreview: config.initialLogo,
                faviconPreview: config.initialFavicon,
                storageDriver: config.storageDriver || 'local',
                ipWhitelistEnabled: Boolean(config.ipWhitelistEnabled),
                ips: Array.isArray(config.ips) ? config.ips : [],
                savedWeekendDays: Array.isArray(config.savedWeekendDays) ? config.savedWeekendDays : [],
                choicesInstance: null,
                alert: {
                    type: 'success',
                    message: '',
                    errors: [],
                },

                init() {
                    const validIds = this.tabs.map(t => t.id);
                    if (!validIds.includes(this.activeTab)) {
                        this.activeTab = 'company';
                    }

                    this.$nextTick(() => {
                        this.initWeekendSelect();
                    });
                },

                selectTab(tabId) {
                    this.activeTab = tabId;
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.pushState({}, '', url);
                },

                handleFilePreview(event, target) {
                    const file = event.target.files?.[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (target === 'logo') {
                            this.logoPreview = e.target.result;
                        } else if (target === 'favicon') {
                            this.faviconPreview = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                },

                addIp() {
                    this.ips.push('');
                },

                removeIp(index) {
                    this.ips.splice(index, 1);
                },

                initWeekendSelect() {
                    const selectEl = this.$refs.weekendSelect;
                    if (!selectEl) return;

                    if (typeof Choices !== 'undefined') {
                        this.choicesInstance = new Choices(selectEl, {
                            removeItemButton: true,
                            shouldSort: false,
                            placeholderValue: 'Select weekend days',
                            searchEnabled: false,
                            itemSelectText: '',
                        });

                        if (this.savedWeekendDays.length > 0) {
                            this.choicesInstance.setChoiceByValue(this.savedWeekendDays);
                        }
                    } else if (this.savedWeekendDays.length > 0) {
                        Array.from(selectEl.options).forEach(opt => {
                            opt.selected = this.savedWeekendDays.includes(opt.value);
                        });
                    }
                },

                async submitForm() {
                    this.saving = true;
                    this.alert = {
                        type: 'success',
                        message: '',
                        errors: []
                    };

                    // Simulate save when no backend endpoint is configured yet
                    if (!config.submitUrl) {
                        await new Promise(resolve => setTimeout(resolve, 450));
                        this.saving = false;
                        this.alert = {
                            type: 'success',
                            message: 'Preview mode: settings state updated locally.',
                            errors: [],
                        };
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                        return;
                    }

                    const formData = new FormData(this.$refs.form);

                    try {
                        const response = await fetch(config.submitUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': config.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const data = await response.json().catch(() => ({}));

                        if (response.ok) {
                            this.alert = {
                                type: 'success',
                                message: data.message || 'Settings saved successfully!',
                                errors: [],
                            };

                            if (data.new_logo_url) {
                                this.logoPreview = data.new_logo_url;
                            }
                            if (data.new_favicon_url) {
                                this.faviconPreview = data.new_favicon_url;
                            }

                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            const flattenedErrors = [];
                            if (data.errors && typeof data.errors === 'object') {
                                Object.values(data.errors).forEach(messages => {
                                    if (Array.isArray(messages)) {
                                        flattenedErrors.push(...messages);
                                    }
                                });
                            }

                            this.alert = {
                                type: 'error',
                                message: data.message || 'Please check the form for errors.',
                                errors: flattenedErrors,
                            };

                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        }
                    } catch (error) {
                        console.error('Settings update error:', error);
                        this.alert = {
                            type: 'error',
                            message: 'An unexpected error occurred while saving settings.',
                            errors: [],
                        };
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    } finally {
                        this.saving = false;
                    }
                },
            };
        }
    </script>
@endpush
