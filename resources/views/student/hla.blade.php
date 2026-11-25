@extends('student.layouts.app')

@section('content')
<div class="py-6 sm:py-10"
     x-data="{
        testType: '',
        classMode: 'Live Online Classes',
        targetScore: '',
        notes: '',
        isSubmitting: false,
        courses: [
            { id: 'IELTS Academic', title: 'IELTS Academic', tag: 'Most Popular', desc: 'For higher education & global university admission', icon: 'lucide:graduation-cap' },
            { id: 'IELTS General', title: 'IELTS General', tag: 'Immigration', desc: 'For work experience, training & migration (UK, CA, AU)', icon: 'lucide:plane' },
            { id: 'PTE Academic', title: 'PTE Academic', tag: 'Fast Results', desc: 'Computer-based English test accepted worldwide', icon: 'lucide:laptop' },
            { id: 'TOEFL iBT', title: 'TOEFL iBT', tag: 'US & Canada', desc: 'Academic English proficiency for North American universities', icon: 'lucide:book-open' },
            { id: 'Duolingo English Test', title: 'Duolingo Test', tag: 'Budget Friendly', desc: 'Convenient, fast & affordable online proficiency test', icon: 'lucide:sparkles' },
            { id: 'Spoken & Phonetics', title: 'Spoken & Fluency', tag: 'Foundation', desc: 'Build confidence, pronunciation & daily communication', icon: 'lucide:mic' }
        ],
        modes: [
            { id: 'In-Person Campus Batch', title: 'In-Person Campus', desc: 'Face-to-face classroom batch', icon: 'lucide:building-2' },
            { id: 'Live Online Classes', title: 'Live Online Batch', desc: 'Interactive Zoom classes from home', icon: 'lucide:video' },
            { id: '1-on-1 Crash Course', title: '1-on-1 Intensive', desc: 'Personalized private coaching', icon: 'lucide:user-check' }
        ]
     }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        {{-- <div class="text-center mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 border border-brand-200/60 dark:border-brand-500/20 mb-3">
                <iconify-icon icon="lucide:sparkles" class="text-sm"></iconify-icon>
                Course Enrollment & Counseling
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 dark:text-white tracking-tight">
                Choose Your Language Program
            </h1>
            <p class="mt-2 text-sm sm:text-base text-neutral-600 dark:text-neutral-400 max-w-xl mx-auto">
                Select the course you want to enroll in and customize your learning preference. Our academic counselor will guide you with batch schedules.
            </p>
        </div> --}}

        <!-- Main Form Card -->
        <form action="#" method="POST" @submit="isSubmitting = true"
              class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border p-4 sm:p-6 border-neutral-200/80 dark:border-neutral-800 overflow-hidden">
            @csrf

            @include('student.step-form.personal-info-hla')

            <hr class="border-neutral-100 dark:border-neutral-800 mt-3 pb-3">

            <div class="space-y-4">

                <!-- Step 1: Select a Course (Interactive Cards) -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-base font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold">2</span>
                            Select Your Desired Course <span class="text-rose-500">*</span>
                        </label>
                    </div>

                    <!-- Hidden input for standard form submission -->
                    <input type="hidden" name="test_type" :value="testType" required>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                        <template x-for="course in courses" :key="course.id">
                            <div @click="testType = course.id"
                                 :class="testType === course.id 
                                    ? 'border-brand-600 bg-brand-50/40 dark:bg-brand-500/10 ring-2 ring-brand-500/20' 
                                    : 'border-neutral-200 dark:border-neutral-800 hover:border-brand-300 dark:hover:border-neutral-700 bg-neutral-50/30 dark:bg-neutral-800/30'"
                                 class="relative cursor-pointer rounded-xl border p-4 transition-all duration-200 flex flex-col justify-between group">
                                
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2.5">
                                        <div class="flex items-center gap-2">
                                        <div :class="testType === course.id ? 'bg-brand-600 text-white' : 'bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 border border-neutral-200 dark:border-neutral-700'"
                                             class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors">
                                            <iconify-icon :icon="course.icon" class="text-lg"></iconify-icon>
                                        </div>
                                        <h3 class="text-sm font-semibold text-neutral-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors"
                                        x-text="course.title"></h3>
                                        </div>

                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400"
                                              x-text="course.tag"></span>
                                    </div>

                                    
                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400 leading-relaxed"
                                       x-text="course.desc"></p>
                                </div>

                                <!-- Selected Checkmark -->
                                <div class="mt-3 pt-2.5 border-t border-neutral-100 dark:border-neutral-800/80 flex items-center justify-between">
                                    <span class="text-xs font-medium"
                                          :class="testType === course.id ? 'text-brand-600 dark:text-brand-400' : 'text-neutral-400'">
                                        <span x-text="testType === course.id ? 'Course Selected' : 'Click to select'"></span>
                                    </span>
                                    <div :class="testType === course.id ? 'border-brand-600 bg-brand-600 text-white' : 'border-neutral-300 dark:border-neutral-600'"
                                         class="w-4 h-4 rounded-full border flex items-center justify-center transition-colors">
                                        <iconify-icon x-show="testType === course.id" icon="lucide:check" class="text-[10px]"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <hr class="border-neutral-100 dark:border-neutral-800">

                <!-- Step 2: Learning Mode -->
                <div>
                    <label class="text-base font-semibold text-neutral-900 dark:text-white flex items-center gap-2 mb-4">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold">3</span>
                        Preferred Learning Mode <span class="text-rose-500">*</span>
                    </label>

                    <input type="hidden" name="class_mode" :value="classMode" required>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <template x-for="mode in modes" :key="mode.id">
                            <div @click="classMode = mode.id"
                                 :class="classMode === mode.id 
                                    ? 'border-brand-600 bg-brand-50/40 dark:bg-brand-500/10 ring-2 ring-brand-500/20' 
                                    : 'border-neutral-200 dark:border-neutral-800 hover:border-neutral-300 dark:hover:border-neutral-700'"
                                 class="cursor-pointer rounded-xl border p-3.5 flex items-center gap-3.5 transition-all">
                                <div :class="classMode === mode.id ? 'text-brand-600 dark:text-brand-400' : 'text-neutral-400'"
                                     class="text-xl shrink-0">
                                    <iconify-icon :icon="mode.icon"></iconify-icon>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-neutral-900 dark:text-white" x-text="mode.title"></div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400" x-text="mode.desc"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <hr class="border-neutral-100 dark:border-neutral-800">

                <!-- Step 3: Additional Details -->
                <div>
                    <label class="text-base font-semibold text-neutral-900 dark:text-white flex items-center gap-2 mb-4">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold">4</span>
                        Goal & Schedule Preferences
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Target Score -->
                        <div class="sm:col-span-2">
                            <label for="target_score" class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Target Score & Tentative Exam Date <span class="text-neutral-400 font-normal">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                                    <iconify-icon icon="lucide:target" class="text-base"></iconify-icon>
                                </div>
                                <input
                                    type="text"
                                    id="target_score"
                                    name="target_score"
                                    x-model="targetScore"
                                    placeholder="Enter your target score..."
                                    class="w-full h-11 pl-10 pr-3.5 text-sm placeholder:text-neutral-400 rounded-xl border border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                                />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="sm:col-span-2">
                            <label for="notes" class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Additional Notes for Academic Counselor <span class="text-neutral-400 font-normal">(Optional)</span>
                            </label>
                            <textarea
                                id="notes"
                                name="notes"
                                x-model="notes"
                                rows="3"
                                placeholder="Write your notes..."
                                class="w-full p-3.5 text-sm rounded-xl border placeholder:text-neutral-400 border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                            ></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer / Submit Bar -->
            <div class="mt-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                    <iconify-icon icon="lucide:shield-check" class="text-base text-emerald-500"></iconify-icon>
                    <span>Free placement test & 1-on-1 counseling included</span>
                </div>

                <button type="submit"
                        :disabled="!testType || isSubmitting"
                        :class="!testType ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-700 shadow-sm'"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/40">
                    <span x-text="isSubmitting ? 'Submitting Request...' : 'Confirm Course Selection'"></span>
                    <iconify-icon icon="lucide:arrow-right" class="text-base"></iconify-icon>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection