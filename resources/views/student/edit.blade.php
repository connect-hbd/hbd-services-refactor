@extends('student.layouts.app')

@section('content')
    {{-- Toast Notification --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
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
                    <p class="text-xs font-semibold text-neutral-900 dark:text-neutral-100">Changes Saved</p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5 truncate">{{ session('success') }}</p>
                </div>

                <button type="button" @click="show = false"
                    class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 transition-colors p-1 rounded-lg">
                    <iconify-icon icon="lucide:x" class="text-sm block"></iconify-icon>
                </button>
            </div>
        </div>
    @endif
    {{-- Main Profile Wizard Section --}}
    @php
        $stepsMeta = [
            1 => ['icon' => 'lucide:id-card', 'label' => 'Personal Info'],
            2 => ['icon' => 'lucide:graduation-cap', 'label' => 'Academic'],
            3 => ['icon' => 'lucide:languages', 'label' => 'English'],
            4 => ['icon' => 'lucide:compass', 'label' => 'Study Plan'],
            5 => ['icon' => 'lucide:briefcase', 'label' => 'Experience'],
            6 => ['icon' => 'lucide:shield-alert', 'label' => 'Immigration'],
            7 => ['icon' => 'lucide:compass', 'label' => 'Referrals'],
        ];
    @endphp

    <main class="max-w-7xl mx-auto relative">  
   
        <div class="pt-0 pb-6 sm:py-6 space-y-6 px-0 sm:px-6 lg:px-8" x-data="stepWizard({{ $activeStep }}, {{ $student->profile_step ?? 1 }})" x-init="init()"
            @go-to-step.window="goTo($event.detail)">

            {{-- ── Responsive Modern Tab Card Container ───────────────────────── --}}
            <div
                class="sticky sm:top-0 top-27 z-[9999] sm:static  sm:z-auto border-t sm:border-t-0  bg-white dark:bg-neutral-900 sm:rounded-2xl border-0 sm:border border-neutral-200/80 dark:border-neutral-800 shadow-sm overflow-hidden">

                {{-- Scroll Container with Edge Visual Indicators --}}
                <div class="relative group">

                    {{-- Left Fade Cue --}}
                    <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white dark:from-neutral-900 to-transparent z-10 transition-opacity duration-200"
                        :class="canScrollLeft ? 'opacity-100' : 'opacity-0'"></div>

                    {{-- Right Fade Cue --}}
                    <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white dark:from-neutral-900 to-transparent z-10 transition-opacity duration-200"
                        :class="canScrollRight ? 'opacity-100' : 'opacity-0'"></div>

                    {{-- Tab Navigation Strip --}}
                    <div class="overflow-x-auto scrollbar-none touch-pan-x border-b border-neutral-200/80 dark:border-neutral-800 select-none cursor-grab active:cursor-grabbing"
                        x-ref="tabContainer" @scroll="updateScrollIndicators()" @wheel="handleDesktopWheel($event)">

                        <nav class="flex items-center min-w-max px-3 sm:px-5 py-1" aria-label="Profile navigation tabs">
                            @foreach ($stepsMeta as $num => $info)
                                <button type="button" x-ref="tab_{{ $num }}"
                                    @click="maxStep >= {{ $num }} && goTo({{ $num }})"
                                    :disabled="maxStep < {{ $num }}"
                                    class="group relative flex items-center gap-2 px-4 py-3.5 my-1 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500"
                                    :class="{
                                        'text-brand-600 dark:text-brand-400 bg-brand-50/70 dark:bg-brand-950/40': currentStep ===
                                            {{ $num }},
                                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100/70 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:bg-neutral-800/60': currentStep !==
                                            {{ $num }} && maxStep >= {{ $num }},
                                        'text-neutral-300 dark:text-neutral-700 cursor-not-allowed opacity-60': maxStep <
                                            {{ $num }}
                                    }">

                                    {{-- Tab Icon Container --}}
                                    <div class="flex h-6 w-6 items-center justify-center rounded-lg transition-all duration-200 flex-shrink-0"
                                        :class="{
                                            'bg-brand-600 text-white dark:bg-brand-500 dark:text-neutral-950 shadow-sm shadow-brand-500/30': currentStep ===
                                                {{ $num }},
                                            'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 group-hover:scale-105': currentStep !==
                                                {{ $num }} && maxStep >= {{ $num }},
                                            'bg-transparent text-neutral-300 dark:text-neutral-700': maxStep <
                                                {{ $num }}
                                        }">
                                        <iconify-icon icon="{{ $info['icon'] }}" class="text-sm"></iconify-icon>
                                    </div>

                                    {{-- Step Label & Indicator --}}
                                    <div class="flex items-center gap-2">
                                        <span>{{ $info['label'] }}</span>
                                    </div>

                                    {{-- Active Bottom Accent Indicator --}}
                                    <span class="absolute inset-x-3 bottom-0 h-0.5 rounded-full transition-all duration-300"
                                        :class="currentStep === {{ $num }} ?
                                            'bg-brand-600 dark:bg-brand-400 scale-x-100' : 'bg-transparent scale-x-0'"></span>
                                </button>
                            @endforeach
                        </nav>
                    </div>
                </div>

                {{-- Compact Dynamic Status Header Strip --}}
                <div
                    class="px-4 sm:px-6 py-2.5 bg-neutral-50/70 dark:bg-neutral-900/40 flex items-center justify-between text-xs text-neutral-500 dark:text-neutral-400">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Step <span class="text-neutral-900 dark:text-neutral-100 font-bold"
                                x-text="currentStep"></span> of 7</span>
                        <span class="text-neutral-300 dark:text-neutral-700">•</span>
                        <span class="font-semibold" x-text="stepLabel()"></span>
                        <button type="button" @click="showGuidelines = true"
                            class="font-semibold uppercase text-red-500 hover:text-red-600 underline underline-offset-4  transition-colors">
                            Form Guidelines
                        </button>
                    </div>
                </div>

            </div>

            {{-- ── Step Form Content Panels ───────────────────────────────────────── --}}
            <div class="mt-6">
                <div x-show="currentStep === 1" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.personal-information')
                    
                </div>

                <div x-show="currentStep === 2" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.academic-information')
                </div>

                <div x-show="currentStep === 3" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.english-proficiency')
                </div>

                <div x-show="currentStep === 4" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.study-plan')
                </div>

                <div x-show="currentStep === 5" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.work-experience')
                </div>

                <div x-show="currentStep === 6" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.immigration-history')
                </div>

                <div x-show="currentStep === 7" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('student.step-form.marketing-section')
                </div>
            </div>

            {{-- ── Step-wise Submission Guidelines Modal ─────────────────────────── --}}
            <div x-show="showGuidelines" x-cloak @keydown.escape.window="showGuidelines = false"
                @click.self="showGuidelines = false" x-transition.opacity
                class="fixed inset-0 z-[10000] flex items-center justify-center bg-neutral-950/60 backdrop-blur-sm p-3 sm:p-6">

                <div x-show="showGuidelines" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    class="relative flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-neutral-900">

                    {{-- Modal Header --}}
                    <div
                        class="flex items-center justify-between border-b border-neutral-200 px-5 py-4 dark:border-neutral-800 sm:px-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                                <iconify-icon icon="lucide:book-open-check" class="text-xl"></iconify-icon>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-neutral-900 dark:text-white">
                                    Submission Guidelines
                                </h2>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    Follow each step to complete your application.
                                </p>
                            </div>
                        </div>

                        <button type="button" @click="showGuidelines = false"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-neutral-400 transition hover:bg-neutral-100 hover:text-neutral-700 dark:hover:bg-neutral-800 dark:hover:text-white">
                            <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
                        </button>
                    </div>

                    

                    {{-- Modal Body --}}
                    <div class="flex-1 overflow-y-auto px-5 py-6 sm:px-6">
                        <div class="mb-5 flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                                <iconify-icon :icon="guidelineSteps[currentStep].icon" class="text-2xl"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-brand-500">
                                    Application Guide
                                </p>
                                <h3 class="text-lg font-bold text-neutral-900 dark:text-white"
                                    x-text="guidelineSteps[currentStep].title"></h3>
                            </div>
                        </div>

                        <p class="mb-5 text-sm leading-6 text-neutral-600 dark:text-neutral-400"
                            x-text="guidelineSteps[currentStep].description"></p>

                        <div class="space-y-3">
                            <template x-for="(item, index) in guidelineSteps[currentStep].items" :key="index">
                                <div
                                    class="flex items-start gap-3 rounded-xl border border-neutral-200/80 bg-neutral-50/70 p-3.5 dark:border-neutral-800 dark:bg-neutral-800/40">
                                    <div
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700 dark:bg-brand-900 dark:text-brand-300">
                                        <span x-text="index + 1"></span>
                                    </div>
                                    <p class="text-sm leading-6 text-neutral-700 dark:text-neutral-300" x-text="item"></p>
                                </div>
                            </template>
                        </div>

                        <div
                            class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/50 dark:bg-amber-950/20">
                            <iconify-icon icon="lucide:info"
                                class="mt-0.5 flex-shrink-0 text-lg text-amber-600 dark:text-amber-400"></iconify-icon>
                            <p class="text-xs leading-5 text-amber-800 dark:text-amber-300"
                                x-text="guidelineSteps[currentStep].note"></p>
                        </div>
                    </div>                  

                </div>
            </div>

        </div>

    </main>
@endsection

@push('scripts')
    <script>
        function stepWizard(initialStep, maxStepFromServer) {
            const labels = {
                1: 'Personal Information',
                2: 'Academic Background',
                3: 'English Proficiency',
                4: 'Study Plan',
                5: 'Work Experience',
                6: 'Immigration History',
                7: 'Referrals',
            };

            return {
                currentStep: initialStep,
                maxStep: maxStepFromServer,
                canScrollLeft: false,
                canScrollRight: false,
                showGuidelines: false,
                labels: labels,

                guidelineSteps: {
                    1: {
                        title: 'Personal Information',
                        icon: 'lucide:user-round',
                        description: 'Provide your personal details exactly as they appear on your official documents.',
                        items: [
                            'Full Name: Enter your name as written in your passport.',
                            'Email and Phone: These can be set once and are locked afterwards.',
                            'Alternative contacts: You may add up to two alternative phone numbers and emails.',
                            'Date of Birth, Gender and Marital Status: Fill in these details carefully.',
                            'Preferred Destination: Select at least one study destination.'
                        ],
                        note: 'Incorrect personal information may cause problems during your admission or visa application.'
                    },
                    2: {
                        title: 'Academic Qualification',
                        icon: 'lucide:graduation-cap',
                        description: 'Provide your highest qualification and complete education history.',
                        items: [
                            'Enter the degree name, institution, graduation date and GPA.',
                            'Use Add Education to include previous education entries.',
                            'Each entry can hold the degree, institution, country, year and grade.',
                            'Remove an entry if you added it by mistake.'
                        ],
                        note: 'Provide accurate academic information and results. You can edit any entry later.'
                    },
                    3: {
                        title: 'English Proficiency',
                        icon: 'lucide:languages',
                        description: 'Tell us about your English test results or your future test plans.',
                        items: [
                            'Select Will Take Exam if you plan to sit for a test, then choose the exam and expected date.',
                            'Select Already Taken if you have completed one or more tests.',
                            'For each exam, select the exam name, year and enter the required scores.',
                            'Click Add Another Exam to include multiple results.'
                        ],
                        note: 'Enter your actual examination results, not estimated scores.'
                    },
                    4: {
                        title: 'Study Plan',
                        icon: 'lucide:compass',
                        description: 'Tell us about your intended degree and the programmes and countries you are considering.',
                        items: [
                            'Select your target degree level.',
                            'For each choice, enter the intended programme and destination country.',
                            'Select your study intake and write a short summary of your academic goals.',
                            'Click Add Preference Choice to add more options and rank them in order.'
                        ],
                        note: 'Put your preferred programme and country first.'
                    },
                    5: {
                        title: 'Work Experience',
                        icon: 'lucide:briefcase-business',
                        description: 'Provide details of your professional experience, if applicable.',
                        items: [
                            'Select Have Work Experience if you have previously worked.',
                            'Enter the role title and the organisation name.',
                            'Provide the start and end dates, or tick Present if you still work there.',
                            'Click Add New Experience to include more roles.'
                        ],
                        note: 'Provide correct employment information and actual employment dates.'
                    },
                    6: {
                        title: 'Immigration History',
                        icon: 'lucide:shield-alert',
                        description: 'Disclose any previous visa refusal history accurately.',
                        items: [
                            'Select Have Visa Refusal if any of your visa applications were refused.',
                            'Specify the country, refusal date and grounds for refusal.',
                            'Upload the refusal letter or supporting document if you have it.',
                            'Click Add Refusal to record multiple refusals.'
                        ],
                        note: 'Incomplete immigration information may affect your application.'
                    },
                    7: {
                        title: 'How Did You Hear About Us?',
                        icon: 'lucide:megaphone',
                        description: 'Tell us how you discovered HBD Services.',
                        items: [
                            'Choose one option that best describes how you heard about us.',
                            'For recommendations, mention the person and their university or country.',
                            'For a phone invitation or expo, select the option and provide details.',
                            'Tick the permission checkbox so we can contact you.'
                        ],
                        note: 'Provide accurate referral information and give contact permission.'
                    }
                },

                init() {
                    this.$nextTick(() => {
                        this.scrollToActiveTab();
                        this.updateScrollIndicators();
                    });

                    // Update scroll indicators on resize
                    window.addEventListener('resize', () => this.updateScrollIndicators());

                    this.$watch('currentStep', (step) => {
                        const url = new URL(window.location.href);
                        url.searchParams.set('step', step);
                        history.replaceState(null, '', url.toString());

                        this.scrollToActiveTab();

                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                },

                goTo(step) {
                    if (step < 1 || step > 7 || step > this.maxStep) return;
                    this.currentStep = step;
                },

                stepLabel() {
                    return labels[this.currentStep] ?? '';
                },

                

                handleDesktopWheel(e) {
                    // Converts vertical mouse wheel scroll into horizontal tab scrolling
                    if (e.deltaY !== 0) {
                        e.preventDefault();
                        this.$refs.tabContainer.scrollLeft += e.deltaY;
                        this.updateScrollIndicators();
                    }
                },

                updateScrollIndicators() {
                    const el = this.$refs.tabContainer;
                    if (!el) return;
                    this.canScrollLeft = el.scrollLeft > 5;
                    this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 5);
                },

                scrollToActiveTab() {
                    const container = this.$refs.tabContainer;
                    const activeTab = this.$refs[`tab_${this.currentStep}`];

                    if (container && activeTab) {
                        const containerWidth = container.offsetWidth;
                        const tabOffsetLeft = activeTab.offsetLeft;
                        const tabWidth = activeTab.offsetWidth;

                        container.scrollTo({
                            left: tabOffsetLeft - (containerWidth / 2) + (tabWidth / 2),
                            behavior: 'smooth'
                        });

                        setTimeout(() => this.updateScrollIndicators(), 300);
                    }
                }
            };
        }
    </script>
@endpush
