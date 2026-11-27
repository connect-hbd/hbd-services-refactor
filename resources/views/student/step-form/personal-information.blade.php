<div
    class="bg-white dark:bg-neutral-900 sm:rounded-2xl shadow-xs border-0 sm:border border-neutral-200/80 dark:border-neutral-800 overflow-hidden">

    {{-- Card Header --}}
    <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-neutral-200/70 dark:border-neutral-800 py-5 px-5 sm:px-8">
        <div class="flex items-center gap-3.5">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                <iconify-icon icon="lucide:id-card" class="text-xl"></iconify-icon>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold tracking-tight text-neutral-900 dark:text-white">
                    Personal &amp; Passport Details
                </h3>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">
                    Add/Update your personal info.
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('student.personal-information.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="px-5 sm:px-8 pt-6 pb-7 dark:bg-neutral-900 space-y-8">

            {{-- ── Personal Information ────────────────────────────────────────── --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-neutral-200/70 pb-3 dark:border-neutral-800">
                    <iconify-icon icon="lucide:user" class="text-brand-500 text-base"></iconify-icon>
                    <h4 class="text-xs font-bold text-neutral-800 uppercase tracking-wider dark:text-neutral-200">
                        Personal Information
                    </h4>
                </div>

                {{-- Primary phone / e-mail are a one-time setup: locked once the e-mail exists --}}
                @php
                    $primaryContactLocked = filled($student->user?->email);
                @endphp

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Full Name  <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name"
                            value="{{ old('name', $student->user?->name) }}"
                            class="w-full h-10 px-3.5 text-sm rounded-lg border border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 placeholder-neutral-400 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                            placeholder="John Doe">
                        @error('name')
                            <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Phone Number  <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="phone"
                                value="{{ old('phone', $student->user?->phone) }}"
                                @readonly($primaryContactLocked)
                                class="w-full h-10 px-3.5 text-sm rounded-lg transition-all
                                    {{ $primaryContactLocked
                                        ? 'pr-20 border-neutral-200/80 bg-neutral-100/80 dark:bg-neutral-800/80 dark:border-neutral-700 text-neutral-500 dark:text-neutral-400 cursor-not-allowed'
                                        : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 placeholder-neutral-400 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500' }}"
                                placeholder="01978855506">
                            @if ($primaryContactLocked)
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-neutral-200/70 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                                        <svg class="w-3 h-3 text-neutral-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Locked
                                    </span>
                                </div>
                            @endif
                        </div>
                        @error('phone')
                            <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Email Address
                            @if (! $primaryContactLocked)
                                <span class="text-rose-500">*</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="email" name="email"
                                value="{{ old('email', $student->user?->email) }}"
                                @readonly($primaryContactLocked)
                                class="w-full h-10 pl-3.5 text-sm rounded-lg transition-all
                                    {{ $primaryContactLocked
                                        ? 'pr-20 border-neutral-200/80 bg-neutral-100/80 dark:bg-neutral-800/80 dark:border-neutral-700 text-neutral-500 dark:text-neutral-400 cursor-not-allowed selection:bg-none'
                                        : 'pr-3.5 border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 placeholder-neutral-400 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500' }}"
                                placeholder="student@example.com">
                            @if ($primaryContactLocked)
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-neutral-200/70 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                                        <svg class="w-3 h-3 text-neutral-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Locked
                                    </span>
                                </div>
                            @endif
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <x-form.form-date id="date_of_birth" name="date_of_birth" label="Date of Birth"
                            placeholder="YYYY-MM-DD" :value="old('date_of_birth', $student->date_of_birth?->format('Y-m-d'))" required />
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Gender <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:users" class="text-sm"></iconify-icon>
                            </div>
                            <select name="gender"
                                class="w-full h-10 pl-10 pr-10 text-sm rounded-lg border appearance-none bg-none
                                {{ $errors->has('gender') ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20' : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                                <option value="">Select Gender</option>
                                <option value="Female" @selected(old('gender', $student->gender) === 'Female')>Female</option>
                                <option value="Male" @selected(old('gender', $student->gender) === 'Male')>Male</option>
                                <option value="Other" @selected(old('gender', $student->gender) === 'Other')>Other</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            </div>
                        </div>
                        @error('gender')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Marital Status --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Marital Status <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:heart" class="text-base"></iconify-icon>
                            </div>
                            <select name="marital_status"
                                class="w-full h-10 pl-10 pr-10 text-sm rounded-lg border appearance-none bg-none
                                {{ $errors->has('marital_status') ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20' : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                                <option value="">Select Marital Status</option>
                                <option value="Single" @selected(old('marital_status', $student->marital_status) === 'Single')>Single</option>
                                <option value="Married" @selected(old('marital_status', $student->marital_status) === 'Married')>Married</option>
                                <option value="Divorced" @selected(old('marital_status', $student->marital_status) === 'Divorced')>Divorced</option>
                                <option value="Widowed" @selected(old('marital_status', $student->marital_status) === 'Widowed')>Widowed</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                            </div>
                        </div>
                        @error('marital_status')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Personal Contact ────────────────────────────────────────── --}}
            @php
                $savedPhones = $student->phones->keyBy('sort_order');
                $savedEmails = $student->emails->keyBy('sort_order');

                $initialPhones = [];
                $initialEmails = [];

                foreach ([0, 1] as $slot) {
                    $phone = $savedPhones->get($slot);
                    $email = $savedEmails->get($slot);

                    $initialPhones[] = [
                        'value' => (string) old("alt_phone.{$slot}.value", $phone?->phone ?? ''),
                        'is_whatsapp' => (bool) old("alt_phone.{$slot}.is_whatsapp", $phone?->is_whatsapp ?? false),
                    ];
                    $initialEmails[] = [
                        'value' => (string) old("alt_email.{$slot}", $email?->email ?? ''),
                    ];
                }
            @endphp

            <div x-data="{
                contacts: {
                    phones: @js($initialPhones),
                    emails: @js($initialEmails)
                }
            }" class="space-y-4">
                <div class="flex items-center gap-2 border-b border-neutral-200/70 pb-3 dark:border-neutral-800">
                    <iconify-icon icon="lucide:phone" class="text-brand-500 text-base"></iconify-icon>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 dark:text-neutral-200">
                        Personal Contact
                    </h4>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Alternative Phone 1 --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Alt Phone One
                        </label>
                        <input type="text" name="alt_phone[0][value]" x-model="contacts.phones[0].value"
                            class="w-full h-10 px-3.5 rounded-lg text-sm border
                                {{ $errors->has('alt_phone.0.value')
                                    ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20'
                                    : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                placeholder:text-neutral-400
                                focus:bg-white dark:focus:bg-neutral-900
                                focus:outline-none focus:ring-2 focus:ring-brand-500/20
                                focus:border-brand-500 transition-all"
                            placeholder="Enter alternative phone...">

                        <label class="mt-2 inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="alt_phone[0][is_whatsapp]" value="0"
                                x-model="contacts.phones[0].is_whatsapp"
                                class="h-4 w-4 rounded border-neutral-300 text-brand-500 focus:ring-brand-500 dark:border-neutral-700 dark:bg-neutral-900">
                            <span class="text-xs font-medium text-neutral-600 dark:text-neutral-400">
                                WhatsApp
                            </span>
                        </label>

                        @error('alt_phone.0.value')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Alternative Phone 2 --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Alt Phone Two
                        </label>
                        <input type="text" name="alt_phone[1][value]" x-model="contacts.phones[1].value"
                            class="w-full h-10 px-3.5 rounded-lg text-sm border
                                {{ $errors->has('alt_phone.1.value')
                                    ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20'
                                    : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                placeholder:text-neutral-400
                                focus:bg-white dark:focus:bg-neutral-900
                                focus:outline-none focus:ring-2 focus:ring-brand-500/20
                                focus:border-brand-500 transition-all"
                            placeholder="Enter alternative phone...">

                        <label class="mt-2 inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="alt_phone[1][is_whatsapp]" value="1"
                                x-model="contacts.phones[1].is_whatsapp"
                                class="h-4 w-4 rounded border-neutral-300 text-brand-500 focus:ring-brand-500 dark:border-neutral-700 dark:bg-neutral-900">
                            <span class="text-xs font-medium text-neutral-600 dark:text-neutral-400">
                                WhatsApp
                            </span>
                        </label>

                        @error('alt_phone.1.value')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Alternative Email 1 --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Alt Email One
                        </label>
                        <input type="text" name="alt_email[0]" x-model="contacts.emails[0].value"
                            class="w-full h-10 px-3.5 rounded-lg text-sm border
                                {{ $errors->has('alt_email.0')
                                    ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20'
                                    : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                placeholder:text-neutral-400
                                focus:bg-white dark:focus:bg-neutral-900
                                focus:outline-none focus:ring-2 focus:ring-brand-500/20
                                focus:border-brand-500 transition-all"
                            placeholder="Enter alternative email...">

                        @error('alt_email.0')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Alternative Email 2 --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Alt Email Two
                        </label>
                        <input type="text" name="alt_email[1]" x-model="contacts.emails[1].value"
                            class="w-full h-10 px-3.5 rounded-lg text-sm border
                                {{ $errors->has('alt_email.1')
                                    ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20'
                                    : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                placeholder:text-neutral-400
                                focus:bg-white dark:focus:bg-neutral-900
                                focus:outline-none focus:ring-2 focus:ring-brand-500/20
                                focus:border-brand-500 transition-all"
                            placeholder="Enter alternative email...">

                        @error('alt_email.1')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Passport Details ────────────────────────────────────────────── --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-neutral-200/70 pb-3 dark:border-neutral-800">
                    <iconify-icon icon="lucide:file-text" class="text-brand-500 text-base"></iconify-icon>
                    <h4 class="text-xs font-bold text-neutral-800 uppercase tracking-wider dark:text-neutral-200">
                        Passport Document Details
                    </h4>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                    {{-- Passport Number --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Passport Number
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:hash" class="text-sm"></iconify-icon>
                            </div>
                            <input type="text" name="passport_number"
                                value="{{ old('passport_number', $student->passport_number) }}"
                                class="w-full h-10 pl-10 pr-4 text-sm placeholder:text-neutral-400 rounded-lg border uppercase
                                    {{ $errors->has('passport_number') ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20' : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                    text-neutral-800 dark:text-neutral-200
                                    focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                                placeholder="e.g. A08923411">
                        </div>
                        @error('passport_number')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Issue Date --}}
                    <div>
                        <x-form.form-date id="passport_issue_date" name="passport_issue_date" label="Issue Date"
                            placeholder="YYYY-MM-DD" :value="old('passport_issue_date', $student->passport_issue_date?->format('Y-m-d'))" />
                    </div>

                    {{-- Expiry Date --}}
                    <div>
                        <x-form.form-date id="passport_expiry_date" name="passport_expiry_date" label="Expiry Date"
                            placeholder="YYYY-MM-DD" :value="old('passport_expiry_date', $student->passport_expiry_date?->format('Y-m-d'))" />
                    </div>

                </div>
            </div>

            {{-- ── Study Destination ────────────────────────────────────────── --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-neutral-200/70 pb-3 dark:border-neutral-800">
                    <iconify-icon icon="lucide:map" class="text-brand-500 text-base"></iconify-icon>
                    <h4 class="text-xs font-bold text-neutral-800 uppercase tracking-wider dark:text-neutral-200">
                        Study Destination
                    </h4>
                </div>

                @php
                    $destinationOptions = $countries
                        ->map(fn ($country) => ['id' => $country->id, 'name' => $country->name])
                        ->values()
                        ->all();
                    $selectedDestinations = $student->destinations->pluck('id')->all();
                @endphp

                <x-form.multi-select name="destination_id" label="Preferred Destination" required
                    :selected="old('destination_id', $selectedDestinations)"
                    placeholder="Select Destination" :options="$destinationOptions" />
            </div>

            {{-- ── Address Details ─────────────────────────────────────────────── --}}
            <div class="space-y-4" x-data="{
                sameAddress: @js((bool) old('same_address', $sameAddress))
            }">

                <div
                    class="flex items-center justify-between border-b border-neutral-200/70 pb-3 dark:border-neutral-800">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:home" class="text-brand-500 text-base"></iconify-icon>
                        <h4 class="text-xs font-bold text-neutral-800 uppercase tracking-wider dark:text-neutral-200">
                            Address Information
                        </h4>
                    </div>
                </div>

                @include('student.profile.partial.country-select', [
                    'prefix' => 'current',
                    'selectedCountry' => old('current_country_id', $currentAddress?->country_id),
                    'selectedCity' => old('current_city_id', $currentAddress?->city_id),
                ])

                <div class="grid grid-cols-1 gap-5">

                    {{-- Current Address --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Current Residential Address
                        </label>
                        <div class="relative">
                            <div
                                class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:map-pin" class="text-sm"></iconify-icon>
                            </div>
                            <textarea name="current_address" rows="2"
                                class="w-full pl-10 pr-4 py-2.5 placeholder:text-neutral-400 rounded-lg border
                                    {{ $errors->has('current_address') ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20' : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                    text-sm text-neutral-800 dark:text-neutral-200
                                    focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                                placeholder="Street, City, Postal Code">{{ old('current_address', $currentAddress?->address) }}</textarea>
                        </div>
                        @error('current_address')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Same Address Checkbox --}}
                    <div class="flex items-center gap-2.5 pt-0.5">
                        <input type="checkbox" id="same_address" name="same_address" value="1"
                            x-model="sameAddress"
                            class="h-4 w-4 rounded border-neutral-300 text-brand-600 focus:ring-brand-500 dark:border-neutral-700 dark:bg-neutral-800">
                        <label for="same_address"
                            class="text-xs font-medium text-neutral-700 dark:text-neutral-300 cursor-pointer select-none">
                            Permanent address is the same
                        </label>
                    </div>

                    {{-- Permanent Address (hidden when same) --}}
                    <div x-show="!sameAddress" x-transition class="space-y-4 pt-1">
                        @include('student.profile.partial.country-select', [
                            'prefix' => 'permanent',
                            'selectedCountry' => old('permanent_country_id', $permanentAddress?->country_id),
                            'selectedCity' => old('permanent_city_id', $permanentAddress?->city_id),
                        ])

                        <div class="relative">
                            <div
                                class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none text-neutral-400">
                                <iconify-icon icon="lucide:building-2" class="text-sm"></iconify-icon>
                            </div>
                            <textarea name="permanent_address" rows="2"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border placeholder:text-neutral-400
                                    {{ $errors->has('permanent_address') ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20' : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                    text-sm text-neutral-800 dark:text-neutral-200
                                    focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                                placeholder="Street, City, Postal Code">{{ old('permanent_address', $permanentAddress?->address) }}</textarea>
                        </div>
                        @error('permanent_address')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Action Buttons ──────────────────────────────────────────────── --}}
            <div
                class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-neutral-200/70 dark:border-neutral-800">
               
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-semibold shadow-xs hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:scale-[0.99] transition-all">
                    <iconify-icon icon="lucide:check" class="text-base"></iconify-icon>
                    Save &amp; Continue
                </button>
            </div>

        </div>
    </form>
</div>
