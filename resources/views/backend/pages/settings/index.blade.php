@extends('backend.layouts.app')

@section('title', 'System Settings')

@section('content')
    @php
        // Static sample data for standalone UI preview & testing
        $companySettings = (object) [
            'company_name' => 'Apex Global Consultancy',
            'logo' => null,
            'favicon' => null,
            'address' => "House 42, Road 11, Banani\nDhaka 1213, Bangladesh",
            'phone' => '+880 1711-000000',
            'email' => 'hello@apexconsultancy.example',
            'website' => 'https://apexconsultancy.example',
        ];

        $settings = collect([
            'max_consecutive_days' => (object) ['value' => 14],
            'working_hours_start' => (object) ['value' => '09:30'],
            'working_hours_end' => (object) ['value' => '18:00'],
            'late_threshold_minutes' => (object) ['value' => 15],
            'early_departure_threshold_minutes' => (object) ['value' => 15],
            'weekend_days' => (object) ['value' => ['5', '6']], // Friday & Saturday
            'storage_driver' => (object) ['value' => 'local'],
            'ip_whitelist_enabled' => (object) ['value' => 1],
            'whitelist_ips' => (object) ['value' => ['103.48.16.24', '192.168.1.10']],
        ]);

        $currentDriver = optional($settings->get('storage_driver'))->value ?? 'local';
        $ipWhitelistEnabled = (bool) (optional($settings->get('ip_whitelist_enabled'))->value ?? 0);

        $ipSetting = $settings->get('whitelist_ips');
        $ipValue = $ipSetting ? $ipSetting->value : [];
        $ips = is_string($ipValue) ? (json_decode($ipValue, true) ?? []) : (array) $ipValue;

        $weekendValue = $settings['weekend_days']->value ?? [];
        $savedWeekendDays = is_string($weekendValue) ? (json_decode($weekendValue, true) ?? []) : (array) $weekendValue;
        $savedWeekendDaysStr = array_map('strval', $savedWeekendDays);
    @endphp

    <div x-data="settingsPage({
        initialTab: new URLSearchParams(window.location.search).get('tab') || 'company',
        submitUrl: '',
        csrfToken: '{{ csrf_token() }}',
        initialLogo: null,
        initialFavicon: null,
        storageDriver: @js($currentDriver),
        ipWhitelistEnabled: @js($ipWhitelistEnabled),
        ips: @js(array_values($ips)),
        savedWeekendDays: @js($savedWeekendDaysStr),
    })" x-init="init()"
        class="min-h-screen bg-slate-50/80 dark:bg-slate-950 py-8 sm:py-10">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Settings &amp; Configuration
                    </h1>
                    <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400 mt-1">
                        Manage organization branding, leave &amp; attendance rules, storage, and security policies.
                    </p>
                </div>
            </div>

            <!-- Feedback Banner (Success / Error) -->
            <div x-show="alert.message" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                :class="alert.type === 'success'
                    ? 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200'
                    : 'bg-rose-50 dark:bg-rose-950/50 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200'"
                class="mb-6 p-4 rounded-xl border shadow-sm flex items-start justify-between gap-4"
                style="display: none;" role="alert">
                <div class="flex items-start gap-3">
                    <template x-if="alert.type === 'success'">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <template x-if="alert.type === 'error'">
                        <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <div class="text-sm">
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
                    class="text-current opacity-60 hover:opacity-100 transition p-1 rounded-lg" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Tabs -->
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-800 p-1.5 mb-6">
                <nav class="flex flex-wrap gap-1" role="tablist" aria-label="Settings sections">
                    <template x-for="tab in tabs" :key="tab.id">
                        <button type="button" role="tab" :aria-selected="activeTab === tab.id"
                            @click="selectTab(tab.id)"
                            :class="activeTab === tab.id
                                ? 'bg-[#1968b2] text-white shadow-sm'
                                : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 flex items-center gap-2 cursor-pointer">
                            <span x-text="tab.label"></span>
                        </button>
                    </template>
                </nav>
            </div>

            <!-- Settings Form -->
            <form x-ref="form" @submit.prevent="submitForm" enctype="multipart/form-data"
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-800 overflow-hidden">
                @csrf

                <div class="p-6 sm:p-8 space-y-8">
                    <!-- ================= COMPANY TAB ================= -->
                    <div x-show="activeTab === 'company'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-8">

                        <!-- Branding Section -->
                        <section class="rounded-xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200/70 dark:border-slate-700/70 p-6">
                            <div class="mb-6">
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Brand Identity</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Your company name, logo, and browser favicon.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="company_name"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Company Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="company_name" type="text" name="company_name"
                                        value="{{ $companySettings->company_name }}" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Logo Upload -->
                                <div>
                                    <label for="logoInput"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Company Logo
                                    </label>
                                    <div x-show="logoPreview"
                                        class="mb-4 p-4 bg-white dark:bg-slate-800/90 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-4">
                                        <div>
                                            <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 mb-2">
                                                Logo Preview
                                            </span>
                                            <img :src="logoPreview" alt="Company Logo"
                                                class="h-16 max-w-[200px] object-contain rounded-lg">
                                        </div>
                                    </div>
                                    <input id="logoInput" type="file" name="logo" accept="image/*"
                                        @change="handleFilePreview($event, 'logo')"
                                        class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#1968b2]/10 file:text-[#1968b2] hover:file:bg-[#1968b2]/20 dark:file:bg-[#1968b2]/20 dark:file:text-sky-300 cursor-pointer transition">
                                </div>

                                <!-- Favicon Upload -->
                                <div>
                                    <label for="faviconInput"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Favicon
                                    </label>
                                    <div x-show="faviconPreview"
                                        class="mb-4 p-4 bg-white dark:bg-slate-800/90 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-4">
                                        <div>
                                            <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 mb-2">
                                                Favicon Preview
                                            </span>
                                            <img :src="faviconPreview" alt="Favicon"
                                                class="h-12 w-12 object-contain rounded-lg border border-slate-200 dark:border-slate-700 p-1">
                                        </div>
                                    </div>
                                    <input id="faviconInput" type="file" name="favicon"
                                        accept=".ico,.png,.jpg,.jpeg,.svg"
                                        @change="handleFilePreview($event, 'favicon')"
                                        class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#1968b2]/10 file:text-[#1968b2] hover:file:bg-[#1968b2]/20 dark:file:bg-[#1968b2]/20 dark:file:text-sky-300 cursor-pointer transition">
                                </div>
                            </div>
                        </section>

                        <!-- Contact Information Section -->
                        <section class="pt-2">
                            <div class="mb-6">
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Contact Information</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Public contact details displayed across correspondence and portals.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="address"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Address
                                    </label>
                                    <textarea id="address" name="address" rows="3"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none resize-none transition">{{ $companySettings->address }}</textarea>
                                </div>

                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Phone
                                    </label>
                                    <input id="phone" type="text" name="phone" value="{{ $companySettings->phone }}"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>

                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Email
                                    </label>
                                    <input id="email" type="email" name="email" value="{{ $companySettings->email }}"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="website"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Website
                                    </label>
                                    <input id="website" type="url" name="website" value="{{ $companySettings->website }}"
                                        placeholder="https://example.com"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- ================= LEAVE & ATTENDANCE TAB ================= -->
                    <div x-show="activeTab === 'leave'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-8"
                        style="display: none;">

                        <!-- Leave Rules -->
                        <section>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Leave Rules</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Configure baseline leave limits for employees.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="max_consecutive_days"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Max Consecutive Leave Days <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="max_consecutive_days" type="number" name="max_consecutive_days"
                                        value="{{ $settings['max_consecutive_days']->value ?? '' }}" required min="1"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>
                            </div>
                        </section>

                        <!-- Working Hours -->
                        <section class="pt-6 border-t border-slate-200/80 dark:border-slate-800">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Working Hours</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Define shift timings and grace thresholds for late check-ins and early departures.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="working_hours_start"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Office Start Time <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="working_hours_start" type="time" name="working_hours_start"
                                        value="{{ $settings['working_hours_start']->value ?? '' }}" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>

                                <div>
                                    <label for="working_hours_end"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Office End Time <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="working_hours_end" type="time" name="working_hours_end"
                                        value="{{ $settings['working_hours_end']->value ?? '' }}" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>

                                <div>
                                    <label for="late_threshold_minutes"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Late Threshold (minutes) <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="late_threshold_minutes" type="number" name="late_threshold_minutes"
                                        value="{{ $settings['late_threshold_minutes']->value ?? '' }}" required min="0"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>

                                <div>
                                    <label for="early_departure_threshold_minutes"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                        Early Departure Threshold (minutes) <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="early_departure_threshold_minutes" type="number"
                                        name="early_departure_threshold_minutes"
                                        value="{{ $settings['early_departure_threshold_minutes']->value ?? '' }}" required min="0"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                </div>
                            </div>
                        </section>

                        <!-- Weekend -->
                        <section class="pt-6 border-t border-slate-200/80 dark:border-slate-800">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Weekend Schedule</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Select weekly off-days used for attendance and leave calculations.</p>

                            <div class="max-w-md">
                                <label for="weekendDaysSelect"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                    Company Weekend Days
                                </label>
                                <select id="weekendDaysSelect" name="weekend_days[]" multiple x-ref="weekendSelect"
                                    class="w-full">
                                    <option value="0">Sunday</option>
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                </select>
                                <p class="text-xs text-slate-400 mt-2">Select one or more weekend days.</p>
                            </div>
                        </section>
                    </div>

                    <!-- ================= FILE STORAGE TAB ================= -->
                    <div x-show="activeTab === 'storage'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6"
                        style="display: none;">

                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">File Storage Driver</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Choose where newly uploaded files are stored. Existing local files remain accessible regardless of this setting.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl">
                            <label @click="storageDriver = 'local'"
                                :class="storageDriver === 'local'
                                    ? 'border-[#1968b2] bg-[#1968b2]/5 dark:bg-[#1968b2]/10 ring-2 ring-[#1968b2]/20'
                                    : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'"
                                class="flex items-start gap-3.5 p-4 rounded-xl border cursor-pointer transition">
                                <input type="radio" name="storage_driver" value="local" x-model="storageDriver"
                                    id="storageDriverLocal"
                                    class="mt-0.5 w-4 h-4 text-[#1968b2] border-slate-300 focus:ring-[#1968b2]">
                                <div>
                                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">Local Storage</span>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5">Store files directly on the application server disk.</span>
                                </div>
                            </label>

                            <label @click="storageDriver = 's3'"
                                :class="storageDriver === 's3'
                                    ? 'border-[#1968b2] bg-[#1968b2]/5 dark:bg-[#1968b2]/10 ring-2 ring-[#1968b2]/20'
                                    : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'"
                                class="flex items-start gap-3.5 p-4 rounded-xl border cursor-pointer transition">
                                <input type="radio" name="storage_driver" value="s3" x-model="storageDriver"
                                    id="storageDriverS3"
                                    class="mt-0.5 w-4 h-4 text-[#1968b2] border-slate-300 focus:ring-[#1968b2]">
                                <div>
                                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">Amazon S3</span>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5">Store uploads in a configured S3 object bucket.</span>
                                </div>
                            </label>
                        </div>

                        <div x-show="storageDriver === 's3'" x-transition style="display: none;"
                            class="max-w-2xl p-4 rounded-xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/60 text-xs text-amber-800 dark:text-amber-200">
                            Ensure your <code class="font-mono font-semibold">AWS_*</code> bucket credentials are configured in your environment before saving.
                        </div>
                    </div>

                    <!-- ================= SECURITY TAB ================= -->
                    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6"
                        style="display: none;">

                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Security &amp; Fraud Prevention</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Restrict attendance actions to approved office or network IP addresses.
                            </p>
                        </div>

                        <!-- Enable IP Whitelist Toggle -->
                        <div class="p-4 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between gap-4">
                            <div>
                                <label for="ip_whitelist_enabled"
                                    class="text-sm font-semibold text-slate-900 dark:text-white cursor-pointer">
                                    Enable IP Whitelist for Attendance
                                </label>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Block check-ins from IP addresses outside the list below when enabled.
                                </p>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="ip_whitelist_enabled" value="0">
                                <input id="ip_whitelist_enabled" type="checkbox" name="ip_whitelist_enabled" value="1"
                                    x-model="ipWhitelistEnabled"
                                    class="w-5 h-5 rounded border-slate-300 text-[#1968b2] focus:ring-[#1968b2] cursor-pointer">
                            </div>
                        </div>

                        <!-- Whitelist IPs List -->
                        <div class="max-w-2xl">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                Whitelisted IP Addresses
                            </label>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                                Only allow access from these IPs. Leave empty to allow all addresses.
                            </p>

                            <template x-if="ips.length === 0">
                                <div
                                    class="mb-4 px-4 py-3 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 text-xs text-slate-400 dark:text-slate-500 italic">
                                    No IP addresses added yet.
                                </div>
                            </template>

                            <div class="space-y-2.5 mb-4">
                                <template x-for="(ip, index) in ips" :key="index">
                                    <div class="flex items-center gap-2.5">
                                        <input type="text" x-model="ips[index]" name="whitelist_ips[]"
                                            placeholder="e.g. 192.168.1.1"
                                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#1968b2]/30 focus:border-[#1968b2] outline-none transition">
                                        <button type="button" @click="removeIp(index)"
                                            class="p-2.5 rounded-xl text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-950/70 transition cursor-pointer"
                                            title="Remove IP">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="addIp()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium bg-[#1968b2]/10 text-[#1968b2] dark:bg-[#1968b2]/20 dark:text-sky-300 hover:bg-[#1968b2]/20 transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add New IP
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sticky / Bottom Action Bar -->
                <div
                    class="px-6 sm:px-8 py-4 bg-slate-50/90 dark:bg-slate-800/60 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end">
                    <button type="submit" :disabled="saving"
                        :class="saving ? 'opacity-70 cursor-not-allowed' : 'hover:bg-[#155b9d] active:scale-[0.99]'"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#1968b2] shadow-sm transition cursor-pointer">
                        <svg x-show="saving" class="animate-spin h-4 w-4 text-white" style="display: none;"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
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
                tabs: [
                    { id: 'company', label: 'Company Settings' },
                    { id: 'leave', label: 'Leave & Attendance' },
                    { id: 'storage', label: 'File Storage' },
                    { id: 'security', label: 'Security & Fraud Prevention' },
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
                    this.alert = { type: 'success', message: '', errors: [] };

                    // Simulate save when no backend endpoint is configured yet
                    if (!config.submitUrl) {
                        await new Promise(resolve => setTimeout(resolve, 450));
                        this.saving = false;
                        this.alert = {
                            type: 'success',
                            message: 'Preview mode: settings state updated locally.',
                            errors: [],
                        };
                        window.scrollTo({ top: 0, behavior: 'smooth' });
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

                            window.scrollTo({ top: 0, behavior: 'smooth' });
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

                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    } catch (error) {
                        console.error('Settings update error:', error);
                        this.alert = {
                            type: 'error',
                            message: 'An unexpected error occurred while saving settings.',
                            errors: [],
                        };
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } finally {
                        this.saving = false;
                    }
                },
            };
        }
    </script>
@endpush