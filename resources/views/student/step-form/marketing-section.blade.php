@php
    $data = array_merge(
        [
            'referral_id' => null,
            'check_student' => (object) [
                'known_by' => "Our Student's recommendation.",
                'known_by_details' => 'Tanvir Ahmed - University of Toronto, Canada',
            ],
            'employees' => [
                (object) ['name' => 'Nusrat Jahan', 'phone' => '01711-234567'],
                (object) ['name' => 'Mahmudul Hasan', 'phone' => '01819-876543'],
                (object) ['name' => 'Farhana Yasmin', 'phone' => '01912-345678'],
                (object) ['name' => 'Sajid Rahman', 'phone' => '01615-987654'],
            ],
            'expos' => [
                'International Education Expo 2026 - Dhaka (Pan Pacific Sonargaon)',
                'UK & Australia Study Fair 2026 - Chattogram',
                'Canada & USA Admission Day 2026 - Sylhet',
                'Global Higher Study Summit 2026 - Uttara',
            ],
        ],
        $data ?? [],
    );

    $student = isset($data['check_student']) ? optional($data['check_student']) : null;

    $initialKnownBy = old('known_by', $student?->known_by);

    if ($initialKnownBy === 'Facebook Add') {
        $initialKnownBy = 'Facebook Ad';
    } elseif ($initialKnownBy === "Our VISA Approved Student's recommendation.") {
        $initialKnownBy = "Our Student's recommendation.";
    }

    $initialKnownBy = $initialKnownBy ?: 'leaflet';

    $textDetailSources = [
        "Our Student's recommendation.",
        'Facebook Group',
        'Friends/Family member recommendation',
        'Other',
    ];

    $rawDetails = $student?->known_by_details ?? '';

    $initialDetails = old('known_by_details', in_array($initialKnownBy, $textDetailSources, true) ? $rawDetails : '');

    $initialEmployee = old('employee', $initialKnownBy === 'Phone call invitation' ? $rawDetails : '');

    $initialExpo = old('expo', $initialKnownBy === 'Attended our Expo.' ? $rawDetails : '');

    $options = [
        [
            'value' => "Our Student's recommendation.",
            'title' => "Our Student's Recommendation",
            'subtitle' => 'আমাদের ভিসা পাওয়া শিক্ষার্থীর মাধ্যমে',
            'icon' => 'fa-user-graduate',
        ],
        [
            'value' => 'leaflet',
            'title' => 'Leaflet',
            'subtitle' => 'লিফলেট',
            'icon' => 'fa-file-lines',
        ],
        [
            'value' => 'Facebook Ad',
            'title' => 'Facebook Ad',
            'subtitle' => 'ফেসবুক বিজ্ঞাপন',
            'icon' => 'fa-bullhorn',
        ],
        [
            'value' => 'Facebook Group',
            'title' => 'Facebook Group',
            'subtitle' => 'ফেসবুক গ্রুপ',
            'icon' => 'fa-users',
        ],
        [
            'value' => 'Friends/Family member recommendation',
            'title' => 'Friends / Family Recommendation',
            'subtitle' => 'বন্ধু বা পরিবারের সদস্যের মাধ্যমে',
            'icon' => 'fa-people-group',
        ],
        [
            'value' => 'Phone call invitation',
            'title' => 'Phone Call Invitation',
            'subtitle' => 'ফোন কলের মাধ্যমে আমন্ত্রণ',
            'icon' => 'fa-phone',
        ],
        [
            'value' => 'Attended our Expo.',
            'title' => 'Attended Our Expo',
            'subtitle' => 'আমাদের এডুকেশন এক্সপোতে অংশগ্রহণ',
            'icon' => 'fa-building-columns',
        ],
        [
            'value' => 'Other',
            'title' => 'Other',
            'subtitle' => 'অন্যান্য মাধ্যম',
            'icon' => 'fa-ellipsis',
        ],
    ];

    $detailConfig = [
        "Our Student's recommendation." => [
            'label' => 'Student Name, University & Country',
            'placeholder' => 'Enter student full name, university name and country...',
            'helper' => 'Please mention the student who recommended our services.',
        ],
        'Facebook Group' => [
            'label' => 'Facebook Group Name / Details',
            'placeholder' => 'Enter the Facebook group name or details...',
            'helper' => 'Mention the Facebook group where you found us.',
        ],
        'Friends/Family member recommendation' => [
            'label' => 'Person Name, University & Country',
            'placeholder' => 'Enter person name, university name and country...',
            'helper' => 'Please mention who recommended our services.',
        ],
        'Other' => [
            'label' => 'Please Specify Other Source',
            'placeholder' => 'Please specify how you heard about us...',
            'helper' => 'Tell us about the source through which you found us.',
        ],
    ];
@endphp

<form action="{{ route('student.referral.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div x-data="{
        knownBy: @js($initialKnownBy),
        details: @js($initialDetails),
        employee: @js($initialEmployee),
        expo: @js($initialExpo),
        detailConfig: @js($detailConfig),
    
        get isTextDetail() {
            return Object.prototype.hasOwnProperty.call(this.detailConfig, this.knownBy);
        },
    
        get isEmployee() {
            return this.knownBy === 'Phone call invitation';
        },
    
        get isExpo() {
            return this.knownBy === 'Attended our Expo.';
        },
    
        get currentDetail() {
            return this.detailConfig[this.knownBy] || {
                label: '',
                placeholder: '',
                helper: ''
            };
        },
    
        get mergedDetails() {
            if (this.isTextDetail) return this.details;
            if (this.isEmployee) return this.employee;
            if (this.isExpo) return this.expo;
            return '';
        },
    
        changeSource(value) {
            if (this.knownBy === value) return;
    
            this.knownBy = value;
            this.details = '';
            this.employee = '';
            this.expo = '';
        }
    }" class="w-full min-w-0 space-y-5">
        @if (empty($data['referral_id']))
            <input type="hidden" name="insert_from" value="FrontDesk">
            <input type="hidden" name="known_by_details" :value="mergedDetails">

            {{-- Referral Source Section --}}
            <section
                class="w-full min-w-0 overflow-hidden sm:rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                {{-- Section Header --}}
                <div class="border-b border-slate-100 px-4 py-4 dark:border-slate-700 sm:px-5">
                    <div class="flex items-center gap-3">
                        {{-- Desktop Icon --}}
                        <div
                            class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 sm:flex">
                            <i class="fa-solid fa-bullseye text-lg"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white sm:text-base">
                                How Did You Know About Us?
                                <span class="text-rose-500">*</span>
                            </h3>

                            <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                                Select one option to let us know how you found us.
                            </p>

                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                আমাদের সম্পর্কে কীভাবে জানতে পেরেছেন তা নির্বাচন করুন।
                            </p>
                        </div>
                    </div>
                </div>

                <div class="min-w-0 p-3 sm:p-5">
                    {{-- Radio Options --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">
                        @foreach ($options as $index => $option)
                            <label for="known_by_{{ $index }}"
                                class="relative flex min-w-0 cursor-pointer items-start gap-2.5 rounded-lg border px-3 py-3 transition-all duration-150 sm:gap-3 sm:px-3.5 sm:py-3.5"
                                :class="knownBy === @js($option['value']) ?
                                    'border-brand-500 bg-brand-50 ring-1 ring-brand-500/20 dark:border-brand-400 dark:bg-brand-500/10' :
                                    'border-slate-200 bg-white hover:border-brand-300 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-slate-500'">
                                <input type="radio" id="known_by_{{ $index }}" name="known_by"
                                    value="{{ $option['value'] }}" :checked="knownBy === @js($option['value'])"
                                    @change="changeSource($event.target.value)" required
                                    class="mt-0.5 h-4 w-4 shrink-0 cursor-pointer border-slate-300 text-brand-600 focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:border-slate-600">

                                {{-- Desktop Icon --}}
                                <span
                                    class="hidden h-9 w-9 shrink-0 items-center justify-center rounded-lg transition-colors sm:flex"
                                    :class="knownBy === @js($option['value']) ?
                                        'bg-brand-600 text-white' :
                                        'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'">
                                    <i class="fa-solid {{ $option['icon'] }} text-sm"></i>
                                </span>

                                <span class="min-w-0 flex-1 pr-4">
                                    <span class="block break-words text-[13px] font-semibold leading-5 sm:text-sm"
                                        :class="knownBy === @js($option['value']) ?
                                            'text-brand-700 dark:text-brand-300' :
                                            'text-slate-800 dark:text-slate-200'">
                                        {{ $option['title'] }}
                                    </span>

                                    <span
                                        class="mt-0.5 block break-words text-[11px] leading-4 text-slate-500 dark:text-slate-400 sm:text-xs">
                                        {{ $option['subtitle'] }}
                                    </span>
                                </span>

                                {{-- Selected Indicator --}}
                                <span x-show="knownBy === @js($option['value'])" x-cloak
                                    class="absolute right-2.5 top-2.5 text-xs font-bold text-brand-600 dark:text-brand-400">
                                    ✓
                                </span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Dynamic Details Field --}}
                    <div x-show="isTextDetail" x-cloak x-transition
                        class="mt-4 rounded-lg border border-brand-200 bg-brand-50/50 p-3 dark:border-brand-500/20 dark:bg-brand-500/5 sm:p-4">
                        <label for="known_by_details_input"
                            class="mb-1.5 block text-xs font-semibold leading-5 text-slate-800 dark:text-slate-200 sm:text-sm"
                            x-text="currentDetail.label"></label>

                        <p class="mb-2 text-[11px] leading-relaxed text-slate-500 dark:text-slate-400"
                            x-text="currentDetail.helper"></p>

                        <textarea id="known_by_details_input" rows="3" x-model="details" :disabled="!isTextDetail"
                            :placeholder="currentDetail.placeholder" :required="isTextDetail"
                            class="block w-full min-w-0 resize-y rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none placeholder:text-xs placeholder:text-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white"></textarea>
                    </div>

                    {{-- Employee Selection --}}
                    <div x-show="isEmployee" x-cloak x-transition
                        class="mt-4 rounded-lg border border-brand-200 bg-brand-50/50 p-3 dark:border-brand-500/20 dark:bg-brand-500/5 sm:p-4">
                        <label for="employee_id"
                            class="mb-2 block text-xs font-semibold text-slate-800 dark:text-slate-200 sm:text-sm">
                            Select Employee <span class="text-rose-500">*</span>
                        </label>

                        <select name="employee" id="employee_id" x-model="employee" :required="isEmployee"
                            :disabled="!isEmployee"
                            class="block w-full min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            <option value="">-- Select Employee --</option>

                            @foreach ($data['employees'] as $employee)
                                <option value="{{ $employee->name }}">
                                    {{ $employee->name }} ({{ $employee->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Expo Selection --}}
                    <div x-show="isExpo" x-cloak x-transition
                        class="mt-4 rounded-lg border border-brand-200 bg-brand-50/50 p-3 dark:border-brand-500/20 dark:bg-brand-500/5 sm:p-4">
                        <label for="expo_id"
                            class="mb-2 block text-xs font-semibold text-slate-800 dark:text-slate-200 sm:text-sm">
                            Select Expo <span class="text-rose-500">*</span>
                        </label>

                        <select name="expo" id="expo_id" x-model="expo" :required="isExpo"
                            :disabled="!isExpo"
                            class="block w-full min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            <option value="">-- Select Expo --</option>

                            @foreach ($data['expos'] as $expo)
                                <option value="{{ $expo }}">
                                    {{ $expo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>
        @endif

        {{-- Contact Permission Section --}}
        <div
            class="w-full min-w-0 overflow-hidden sm:rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="p-4 sm:p-5">
                <label for="permissionCheckbox" class="flex min-w-0 cursor-pointer items-start gap-2.5 sm:gap-3">
                    <input type="checkbox" name="permission" id="permissionCheckbox" value="granted" required
                        @checked(old('permission') === 'granted')
                        class="mt-1 h-4 w-4 shrink-0 rounded border-slate-300 text-brand-600 focus:ring-2 focus:ring-brand-500 dark:border-slate-600">

                    <span class="min-w-0 flex-1">
                        <span
                            class="block break-words text-xs font-bold leading-5 text-slate-900 dark:text-white sm:text-sm">
                            Check for Permission to Contacting You
                            <span class="text-rose-500">*</span>
                        </span>

                        <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                            যোগাযোগের অনুমতি প্রদান করুন
                        </span>
                    </span>

                    {{-- Desktop Icon --}}
                    <span
                        class="hidden h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 sm:flex">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                </label>

                <div class="mt-4 space-y-3 border-t border-slate-100 pt-3 dark:border-slate-700">
                    <p class="break-words text-[11px] leading-relaxed text-slate-600 dark:text-slate-400 sm:text-xs">
                        By ticking this box, you agree to let HBD Services contact you for further assistance.

                        <span class="mt-1 block text-slate-500 dark:text-slate-500">
                            এই বক্সে টিক দিলে HBD Services আপনাকে প্রয়োজনীয় সহায়তার জন্য যোগাযোগ করতে পারবে।
                        </span>
                    </p>

                    <p class="break-words text-[11px] leading-relaxed text-slate-600 dark:text-slate-400 sm:text-xs">
                        We collect the above information to provide services to you or inform you about services we may
                        offer.

                        <span class="mt-1 block text-slate-500 dark:text-slate-500">
                            আপনাকে সেবা প্রদান এবং আমাদের অন্যান্য সেবা সম্পর্কে জানাতে আমরা এই তথ্য সংগ্রহ করি।
                        </span>
                    </p>
                </div>
                {{-- FOOTER --}}
                <div
                    class="flex items-center justify-between gap-3 border-t border-neutral-100 pt-4 dark:border-neutral-800">
                    <button type="button" @click="$dispatch('go-to-step', 6)"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-neutral-200 px-4 py-2 text-sm font-semibold text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        <iconify-icon icon="lucide:arrow-left" class="text-sm"></iconify-icon>
                        Back
                    </button>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-xs transition-colors hover:bg-emerald-700">
                            <iconify-icon icon="lucide:check" class="text-sm"></iconify-icon>
                            Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</form>
