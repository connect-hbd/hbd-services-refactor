@extends('frontend.layouts.app')

@section('content')
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
        <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="grid grid-cols-1 lg:grid-cols-12">

                <!-- Left Info Panel -->
                <div class="lg:col-span-5 relative flex flex-col justify-between p-5 sm:p-6 text-white bg-cover bg-center"
                    style="background-image: url('{{ asset('frontend-img/api-form.jpg') }}');">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#1968b2]/95 via-[#155b9d]/90 to-slate-900/95"></div>

                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase bg-white/15 backdrop-blur-sm rounded-full mb-6 border border-white/20">
                            Free Counselling
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-bold leading-tight mb-4">
                            Ready to Make Your Study Abroad Dreams a Reality?
                        </h2>
                        <p class="text-blue-100 text-sm sm:text-base leading-relaxed">
                            Share your details and let our expert counsellors guide you through your study options,
                            admissions, and next steps towards your future.
                        </p>
                    </div>

                    <div
                        class="relative z-10 mt-10 pt-6 border-t border-white/15 gap-3 text-sm text-blue-50 flex items-center">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Free Entry
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Secure Registration
                        </div>
                    </div>
                </div>

                <!-- Right Form Panel -->
                <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between" x-data="{
                    step: 1,
                    isSubmitting: false,
                    isSubmitted: false,
                    errorMessage: '',
                    education: '',
                    hasEnglishTest: '',
                    englishTestName: '',
                    today: new Date().toISOString().split('T')[0],
                
                    resetEnglishFields() {
                        if (this.hasEnglishTest !== 'yes') {
                            this.englishTestName = '';
                        }
                    },
                
                    nextStep() {
                        this.errorMessage = '';
                        const step1Fields = this.$refs.step1.querySelectorAll('input:not([disabled]), select:not([disabled])');
                        for (const field of step1Fields) {
                            if (!field.checkValidity()) {
                                field.reportValidity();
                                return;
                            }
                        }
                        this.step = 2;
                    },
                
                    prevStep() {
                        this.errorMessage = '';
                        this.step = 1;
                    },
                
                    async submitForm() {
                        this.errorMessage = '';
                        if (!this.$refs.form.checkValidity()) {
                            this.$refs.form.reportValidity();
                            return;
                        }
                
                        this.isSubmitting = true;
                
                        try {
                            const formData = new FormData(this.$refs.form);
                            const response = await fetch(this.$refs.form.action || window.location.href, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                                },
                                body: formData
                            });
                
                            const data = await response.json().catch(() => ({}));
                
                            if (!response.ok) {
                                if (data.errors) {
                                    const firstError = Object.values(data.errors)[0][0];
                                    throw new Error(firstError);
                                }
                                throw new Error(data.message || 'Something went wrong. Please try again.');
                            }
                
                            this.isSubmitted = true;
                        } catch (err) {
                            this.errorMessage = err.message;
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                
                    resetAll() {
                        this.$refs.form.reset();
                        this.education = '';
                        this.hasEnglishTest = '';
                        this.englishTestName = '';
                        this.errorMessage = '';
                        this.isSubmitted = false;
                        this.step = 1;
                    }
                }">

                    <!-- Header & Step Progress Indicator -->
                    <div class="mb-6" x-show="!isSubmitted">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Registration</h3>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5"
                                    x-text="step === 1 ? 'Step 1 of 2 — Personal & Academic Info' : 'Step 2 of 2 — English Proficiency & Preferences'">
                                </p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#1968b2]/10 text-[#1968b2]"
                                x-text="step === 1 ? '50%' : '100%'">
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#1968b2] transition-all duration-300 ease-out rounded-full"
                                :style="step === 1 ? 'width: 50%' : 'width: 100%'">
                            </div>
                        </div>
                    </div>

                    <!-- Global Error Alert -->
                    <div x-show="errorMessage !== ''" x-transition style="display: none;"
                        class="mb-4 p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <!-- Main Form (No Reload) -->
                    <form id="event-form" action="{{ route('appointment-store') }}" method="POST" x-ref="form"
                        @submit.prevent="submitForm()" x-show="!isSubmitted" novalidate>
                        <div class="grid grid-cols-1 grid-rows-1 min-h-102.5">

                            <!-- ==================== STEP 1 ==================== -->
                            <div x-ref="step1"
                                class="col-start-1 row-start-1 flex flex-col justify-between transition-all duration-300 ease-out"
                                :class="step === 1 ? 'opacity-100 translate-x-0 pointer-events-auto z-10' :
                                    'opacity-0 -translate-x-4 pointer-events-none z-0'">

                                <div class="space-y-4">
                                    <!-- First & Last Name -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">
                                                First Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="first_name" name="first_name"
                                                placeholder="e.g. Tanvir" required
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                        </div>

                                        <div>
                                            <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">
                                                Last Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="last_name" name="last_name" placeholder="e.g. Ahmed"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                        </div>
                                    </div>

                                    <!-- Email & Phone -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                                                Email Address <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" id="email" name="email"
                                                placeholder="you@example.com" required
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                        </div>

                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">
                                                Phone Number <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" id="phone" name="phone" placeholder="017XXXXXXXX"
                                                pattern="^[0-9+\-\s()]{7,20}$" title="Please enter a valid phone number"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div>
                                        <label for="full_address" class="block text-sm font-medium text-slate-700 mb-1">
                                            Which city are you from? <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="full_address" name="full_address"
                                            placeholder="e.g. Dhaka, Chattogram, Sylhet" required
                                            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                    </div>

                                    <!-- Education & Institution -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="aq_exam_name"
                                                class="block text-sm font-medium text-slate-700 mb-1">
                                                Highest Level of Education <span class="text-red-500">*</span>
                                            </label>
                                            <select id="aq_exam_name" name="aq_exam_name" x-model="education" required
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                                <option value="" disabled selected>Select an option</option>
                                                <option value="SSC">SSC</option>
                                                <option value="HSC">HSC</option>
                                                <option value="Diploma">Diploma</option>
                                                <option value="O level">O level</option>
                                                <option value="A level">A level</option>
                                                <option value="Bachelor's">Bachelor's</option>
                                                <option value="Hon's">Hon's</option>
                                                <option value="Master's">Master's</option>
                                                <option value="MBBS">MBBS</option>
                                            </select>
                                        </div>

                                        <div class="transition-opacity duration-200"
                                            :class="education !== '' ? 'opacity-100' : 'opacity-45'">
                                            <label for="aq_college_name"
                                                class="block text-sm font-medium text-slate-700 mb-1">
                                                School / College / University Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="aq_college_name" name="aq_college_name"
                                                :placeholder="education !== '' ? 'Enter your ' + education +
                                                    ' institution name' : 'Select education level first'"
                                                :required="education !== ''" :disabled="education === ''"
                                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20 disabled:bg-slate-50 disabled:cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 1 Footer -->
                                <div class="pt-6 mt-auto border-t border-slate-100 flex justify-end">
                                    <button type="button" @click="nextStep()"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-[#1968b2] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#155b9d] focus:outline-none focus:ring-2 focus:ring-[#1968b2] focus:ring-offset-2 cursor-pointer">
                                        <span>Continue</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- ==================== STEP 2 ==================== -->
                            <div x-ref="step2"
                                class="col-start-1 row-start-1 flex flex-col justify-between transition-all duration-300 ease-out"
                                :class="step === 2 ? 'opacity-100 translate-x-0 pointer-events-auto z-10' :
                                    'opacity-0 translate-x-4 pointer-events-none z-0'">

                                <div class="space-y-4">
                                    <!-- English Proficiency Status -->
                                    <div>
                                        <label for="is_attend_english_proficiency_test"
                                            class="block text-sm font-medium text-slate-700 mb-1">
                                            Have you given IELTS / PTE / TOEFL? <span class="text-red-500">*</span>
                                        </label>
                                        <select id="is_attend_english_proficiency_test"
                                            name="is_attend_english_proficiency_test" x-model="hasEnglishTest"
                                            @change="resetEnglishFields()" :required="step === 2" :disabled="step !== 2"
                                            class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                            <option value="" disabled selected>Select an option</option>
                                            <option value="yes">Yes, I have my score</option>
                                            <option value="will_sit">No, planning to sit soon</option>
                                        </select>
                                    </div>

                                    <!-- English Test Name & Overall Score -->
                                    <div
                                        class="grid grid-cols-1 grid-rows-1 rounded-xl bg-slate-50 border border-slate-200/80 overflow-hidden">

                                        <!-- State 0: Empty / Not Selected -->
                                        <div class="col-start-1 row-start-1 p-4 flex items-center justify-center text-center transition-opacity duration-200"
                                            :class="hasEnglishTest === '' ? 'opacity-100 pointer-events-auto z-10' :
                                                'opacity-0 pointer-events-none z-0'">
                                            <p class="text-xs sm:text-sm text-slate-400">
                                                Select whether you have taken a test or plan to sit soon.
                                            </p>
                                        </div>

                                        <!-- State 1: YES -> Test Name & Overall Score -->
                                        <div class="col-start-1 row-start-1 p-4 grid grid-cols-1 sm:grid-cols-2 gap-4 items-start transition-opacity duration-200"
                                            :class="hasEnglishTest === 'yes' ? 'opacity-100 pointer-events-auto z-10' :
                                                'opacity-0 pointer-events-none z-0'">
                                            <div>
                                                <label for="ep_exam_name"
                                                    class="block text-sm font-medium text-slate-700 mb-1">
                                                    Which Test have you given? <span class="text-red-500">*</span>
                                                </label>
                                                <select id="ep_exam_name" name="ep_exam_name" x-model="englishTestName"
                                                    :required="step === 2 && hasEnglishTest === 'yes'"
                                                    :disabled="step !== 2 || hasEnglishTest !== 'yes'"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                                    <option value="" disabled selected>Select test</option>
                                                    <option value="Ielts">IELTS</option>
                                                    <option value="Pte">PTE</option>
                                                    <option value="Toefl">TOEFL</option>
                                                    <option value="OIETC">OIETC</option>
                                                    <option value="Duolingo">Duolingo</option>
                                                </select>
                                            </div>

                                            <div class="transition-opacity duration-200"
                                                :class="englishTestName !== '' ? 'opacity-100' : 'opacity-45'">
                                                <label for="ep_overall"
                                                    class="block text-sm font-medium text-slate-700 mb-1">
                                                    Overall Score <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" step="0.1" min="0" max="160"
                                                    id="ep_overall" name="ep_overall"
                                                    :placeholder="englishTestName !== '' ? 'Enter ' + englishTestName +
                                                        ' score' : 'Select test first'"
                                                    :required="step === 2 && hasEnglishTest === 'yes' &&
                                                        englishTestName !== ''"
                                                    :disabled="step !== 2 || hasEnglishTest !== 'yes' ||
                                                        englishTestName === ''"
                                                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                            </div>
                                        </div>

                                        <!-- State 2: NO -> Tentative Exam Date (No Past Dates) -->
                                        <div class="col-start-1 row-start-1 p-4 flex flex-col justify-center transition-opacity duration-200"
                                            :class="hasEnglishTest === 'will_sit' ? 'opacity-100 pointer-events-auto z-10' :
                                                'opacity-0 pointer-events-none z-0'">
                                            <label for="ep_tentative_date"
                                                class="block text-sm font-medium text-slate-700 mb-1">
                                                Tentative date you are planning to sit for the exam? <span
                                                    class="text-red-500">*</span>
                                            </label>
                                            <input type="date" id="ep_tentative_date" name="ep_tentative_date"
                                                :min="today"
                                                :required="step === 2 && hasEnglishTest === 'will_sit'"
                                                :disabled="step !== 2 || hasEnglishTest !== 'will_sit'"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#1968b2] focus:outline-none focus:ring-2 focus:ring-[#1968b2]/20">
                                            <p class="text-xs text-slate-500 mt-1.5">Past dates are disabled. Choose today
                                                or a future date.</p>
                                        </div>

                                    </div>

                                    <!-- Study Destinations -->
                                    <div>
                                        <x-form.multi-select name="study_destinations[]" label="Study Destinations"
                                            :options="$countries
                                                ->map(
                                                    fn($country) => [
                                                        'id' => (string) $country->id,
                                                        'name' => $country->name,
                                                    ],
                                                )
                                                ->values()" :selected="old('study_destinations', $employee?->study_destinations ?? [])" placeholder="Select destinations..." />
                                    </div>

                                    <!-- Consent Checkbox -->
                                    <div class="flex items-start gap-3 pt-1">
                                        <input type="checkbox" id="consent" name="consent" value="1"
                                            :required="step === 2" :disabled="step !== 2"
                                            class="mt-1 h-4 w-4 rounded border-slate-300 text-[#1968b2] focus:ring-[#1968b2] cursor-pointer">
                                        <label for="consent"
                                            class="text-xs sm:text-sm text-slate-600 leading-relaxed cursor-pointer">
                                            I’m interested and agree to be contacted for confirmation.<span
                                                class="text-red-500">*</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Step 2 Footer Buttons -->
                                <div
                                    class="pt-6 mt-auto border-t border-slate-100 flex items-center justify-between gap-3">
                                    <button type="button" @click="prevStep()" :disabled="isSubmitting"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 disabled:opacity-50 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Back</span>
                                    </button>

                                    <button type="submit" :disabled="isSubmitting"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1968b2] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#155b9d] focus:outline-none focus:ring-2 focus:ring-[#1968b2] focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer">
                                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        <svg x-show="isSubmitting" style="display: none;" class="w-4 h-4 animate-spin"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        <span x-text="isSubmitting ? 'Submitting...' : 'Register Now'"></span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>

                    <!-- ==================== SUCCESS STATE (NO RELOAD) ==================== -->
                    <div x-show="isSubmitted" x-transition.opacity.duration.300ms style="display: none;"
                        class="my-auto py-10 text-center space-y-4">
                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800">Registration Successful!</h4>
                        <p class="text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                            Thank you for registering. Our study abroad counselling team has received your details and will
                            contact you
                            shortly.
                        </p>
                        <div class="pt-3">
                            <button type="button" @click="resetAll()"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 cursor-pointer">
                                Register Another Student
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
