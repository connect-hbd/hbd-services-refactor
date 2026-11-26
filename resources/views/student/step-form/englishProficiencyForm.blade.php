<div x-data="englishProficiencyForm()" class="space-y-6">

    {{-- 1. Exam Status --}}
    <section class="space-y-4">
        <div class="flex items-center gap-2 border-b border-neutral-100 pb-3 dark:border-neutral-800">
            <iconify-icon icon="lucide:graduation-cap" class="text-brand-500 text-lg"></iconify-icon>
            <h4 class="text-sm font-bold text-neutral-900 dark:text-white">
                3. English Proficiency Status
            </h4>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

            <label class="cursor-pointer">
                <input type="radio" name="status" value="will" x-model="status" class="peer sr-only">

                <div
                    class="flex items-center gap-3 rounded-xl border border-neutral-200 p-4 transition
                    peer-checked:border-brand-500 peer-checked:bg-brand-50/50
                    dark:border-neutral-700 dark:peer-checked:border-brand-500 dark:peer-checked:bg-brand-950/20">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40">
                        <iconify-icon icon="lucide:calendar-clock" class="text-xl"></iconify-icon>
                    </div>

                    <div>
                        <h5 class="text-sm font-bold text-neutral-900 dark:text-white">
                            Will Take Exam
                        </h5>
                        <p class="mt-1 text-xs text-neutral-500">
                            I am planning to take an exam
                        </p>
                    </div>

                    <div
                        class="ml-auto flex h-5 w-5 items-center justify-center rounded-full border border-neutral-300 text-transparent peer-checked:bg-brand-600 peer-checked:text-white">
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                </div>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="status" value="yes" x-model="status" class="peer sr-only">

                <div
                    class="flex items-center gap-3 rounded-xl border border-neutral-200 p-4 transition
                    peer-checked:border-brand-500 peer-checked:bg-brand-50/50
                    dark:border-neutral-700 dark:peer-checked:border-brand-500 dark:peer-checked:bg-brand-950/20">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40">
                        <iconify-icon icon="lucide:badge-check" class="text-xl"></iconify-icon>
                    </div>

                    <div>
                        <h5 class="text-sm font-bold text-neutral-900 dark:text-white">
                            Already Taken
                        </h5>
                        <p class="mt-1 text-xs text-neutral-500">
                            I have completed an exam
                        </p>
                    </div>

                    <div
                        class="ml-auto flex h-5 w-5 items-center justify-center rounded-full border border-neutral-300 text-transparent peer-checked:bg-brand-600 peer-checked:text-white">
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                </div>
            </label>

        </div>
    </section>


    {{-- 2. Planned Exam --}}
    <section x-show="status === 'will'" x-cloak
        class="space-y-4 rounded-xl border border-blue-200 bg-blue-50/30 p-4 dark:border-blue-900 dark:bg-blue-950/10">

        <div class="flex items-center gap-2">
            <iconify-icon icon="lucide:calendar-check" class="text-blue-600"></iconify-icon>
            <h4 class="text-sm font-bold text-neutral-900 dark:text-white">
                Planned Examination
            </h4>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Exam Name <span class="text-rose-500">*</span>
                </label>

                <select name="planned_exam_name" :required="status === 'will'"
                    class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-brand-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">

                    <option value="">Select Exam</option>
                    <option value="IELTS Academic">IELTS Academic</option>
                    <option value="IELTS General Training">IELTS General Training</option>
                    <option value="GRE General">GRE General</option>
                    <option value="PTE Academic">PTE Academic</option>
                    <option value="TOEFL iBT">TOEFL iBT</option>
                    <option value="Duolingo English Test">Duolingo English Test</option>
                </select>
            </div>

            <div>
                <x-form.form-date id="exam_date" name="exam_date" label="Expected Exam Date" :required="status === 'will'"
                    placeholder="YYYY-MM-DD" :value="old('exam_date')" required />
            </div>

        </div>
    </section>


    {{-- 3. Completed Exams --}}
    <section x-show="status === 'yes'" x-cloak class="space-y-4">

        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-3 dark:border-neutral-800">
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:file-check-2" class="text-brand-500 text-lg"></iconify-icon>
                <h4 class="text-sm font-bold text-neutral-900 dark:text-white">
                    Completed Examination Details
                </h4>
            </div>

            <span
                class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-600 dark:bg-brand-950/40 dark:text-brand-400">
                <span x-text="exams.length"></span> Exams
            </span>
        </div>

        <template x-for="(exam, index) in exams" :key="exam.id">
            <div
                class="overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">

                {{-- Exam Header --}}
                <div
                    class="flex items-center justify-between gap-3 border-b border-neutral-100 bg-neutral-50/70 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-800/30">

                    <div class="flex items-center gap-2">
                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-100 text-xs font-bold text-brand-600 dark:bg-brand-950 dark:text-brand-400"
                            x-text="index + 1"></span>

                        <h5 class="text-sm font-bold text-neutral-800 dark:text-white" x-text="'Exam ' + (index + 1)">
                        </h5>
                    </div>

                    <button type="button" x-show="exams.length > 1" @click="removeExam(index)"
                        class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                        Remove
                    </button>
                </div>

                <div class="space-y-5 p-4">

                    {{-- Exam Name and Year --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                Exam Name <span class="text-rose-500">*</span>
                            </label>

                            <select x-model="exam.exam_name" :name="'exams[' + index + '][exam_name]'" required
                                class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-brand-500 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white">

                                <option value="">Select Exam</option>
                                <option value="IELTS Academic">IELTS Academic</option>
                                <option value="IELTS General Training">IELTS General Training</option>
                                <option value="GRE General">GRE General</option>
                                <option value="PTE Academic">PTE Academic</option>
                                <option value="TOEFL iBT">TOEFL iBT</option>
                                <option value="Duolingo English Test">Duolingo English Test</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                Exam Year <span class="text-rose-500">*</span>
                            </label>

                            <select x-model="exam.year" :name="'exams[' + index + '][year]'" required
                                class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-brand-500 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white">

                                <option value="">Select Year</option>
                                <template x-for="year in years" :key="year">
                                    <option :value="year" x-text="year"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    {{-- Dynamic Score Fields --}}
                    <div x-show="exam.exam_name" x-cloak class="space-y-3">

                        <div
                            class="flex items-center justify-between border-t border-neutral-100 pt-4 dark:border-neutral-800">
                            <h6 class="text-xs font-bold text-neutral-800 dark:text-white">
                                Examination Scores
                            </h6>

                            <span class="text-[10px] text-neutral-400" x-text="exam.exam_name"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">

                            <template x-for="field in scoreFields(exam.exam_name)" :key="field.key">
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold"
                                        :class="field.key === 'overall' || field.key === 'total' ?
                                            'text-brand-600 dark:text-brand-400' :
                                            'text-neutral-600 dark:text-neutral-400'"
                                        x-text="field.label">
                                    </label>

                                    <input type="tel" :step="field.step || '0.5'" :min="field.min"
                                        :max="field.max" :placeholder="field.placeholder"
                                        x-model="exam.scores[field.key]"
                                        :name="'exams[' + index + '][scores][' + field.key + ']'"
                                        :required="field.required" inputmode="numeric"
                                        class="w-full rounded-xl border border-neutral-200 placeholder:text-neutral-400 bg-white px-3 py-2.5 text-sm font-semibold text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white">
                                </div>
                            </template>

                        </div>
                    </div>

                    {{-- Select Exam Message --}}
                    <div x-show="!exam.exam_name"
                        class="rounded-lg bg-neutral-50 p-3 text-xs text-neutral-500 dark:bg-neutral-800">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Select an exam name to display its score fields.
                    </div>

                </div>
            </div>
        </template>

        {{-- Add Another Exam --}}
        <button type="button" @click="addExam()"
            class="flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-brand-300 bg-brand-50/30 px-4 py-3 text-sm font-semibold text-brand-600 transition hover:bg-brand-50 dark:border-brand-800 dark:bg-brand-950/10 dark:text-brand-400">
            <iconify-icon icon="lucide:plus-circle" class="text-lg"></iconify-icon>
            Add Another Exam
        </button>

    </section>

</div>

<script>
    function englishProficiencyForm() {
        return {
            status: '',

            exams: [],

            years: Array.from({
                    length: 15
                },
                (_, i) => new Date().getFullYear() - i
            ),

            addExam() {
                this.exams.push({
                    id: Date.now() + Math.random(),
                    exam_name: '',
                    year: '',
                    scores: {}
                });
            },

            removeExam(index) {
                this.exams.splice(index, 1);
            },

            scoreFields(examName) {
                const fields = {
                    'IELTS Academic': [{
                            key: 'overall',
                            label: 'Overall Score',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'listening',
                            label: 'Listening',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'reading',
                            label: 'Reading',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'writing',
                            label: 'Writing',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'speaking',
                            label: 'Speaking',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        }
                    ],

                    'IELTS General Training': [{
                            key: 'overall',
                            label: 'Overall Score',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'listening',
                            label: 'Listening',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'reading',
                            label: 'Reading',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'writing',
                            label: 'Writing',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'speaking',
                            label: 'Speaking',
                            min: 0,
                            max: 9,
                            step: 0.5,
                            placeholder: '0.0',
                            required: true
                        }
                    ],

                    'GRE General': [{
                            key: 'overall',
                            label: 'Overall Score',
                            min: 0,
                            max: 170,
                            step: 1,
                            placeholder: '0.0',
                            required: true
                        },
                        {
                            key: 'verbal',
                            label: 'Verbal Reasoning',
                            min: 130,
                            max: 170,
                            step: 1,
                            placeholder: '130–170',
                            required: true
                        },
                        {
                            key: 'quantitative',
                            label: 'Quantitative Reasoning',
                            min: 130,
                            max: 170,
                            step: 1,
                            placeholder: '130–170',
                            required: true
                        },
                        {
                            key: 'awa',
                            label: 'Analytical Writing (AWA)',
                            min: 0,
                            max: 6,
                            step: 0.5,
                            placeholder: '0–6',
                            required: true
                        }
                    ],

                    'PTE Academic': [{
                            key: 'overall',
                            label: 'Overall Score',
                            min: 10,
                            max: 90,
                            step: 1,
                            placeholder: '10–90',
                            required: true
                        },
                        {
                            key: 'listening',
                            label: 'Listening',
                            min: 10,
                            max: 90,
                            step: 1,
                            placeholder: '10–90',
                            required: true
                        },
                        {
                            key: 'reading',
                            label: 'Reading',
                            min: 10,
                            max: 90,
                            step: 1,
                            placeholder: '10–90',
                            required: true
                        },
                        {
                            key: 'writing',
                            label: 'Writing',
                            min: 10,
                            max: 90,
                            step: 1,
                            placeholder: '10–90',
                            required: true
                        },
                        {
                            key: 'speaking',
                            label: 'Speaking',
                            min: 10,
                            max: 90,
                            step: 1,
                            placeholder: '10–90',
                            required: true
                        }
                    ],

                    'TOEFL iBT': [{
                            key: 'total',
                            label: 'Total Score',
                            min: 0,
                            max: 120,
                            step: 1,
                            placeholder: '0–120',
                            required: true
                        },
                        {
                            key: 'reading',
                            label: 'Reading',
                            min: 0,
                            max: 30,
                            step: 1,
                            placeholder: '0–30',
                            required: true
                        },
                        {
                            key: 'listening',
                            label: 'Listening',
                            min: 0,
                            max: 30,
                            step: 1,
                            placeholder: '0–30',
                            required: true
                        },
                        {
                            key: 'speaking',
                            label: 'Speaking',
                            min: 0,
                            max: 30,
                            step: 1,
                            placeholder: '0–30',
                            required: true
                        },
                        {
                            key: 'writing',
                            label: 'Writing',
                            min: 0,
                            max: 30,
                            step: 1,
                            placeholder: '0–30',
                            required: true
                        }
                    ],

                    'Duolingo English Test': [{
                            key: 'overall',
                            label: 'Overall Score',
                            min: 10,
                            max: 160,
                            step: 5,
                            placeholder: '10–160',
                            required: true
                        },
                        {
                            key: 'literacy',
                            label: 'Literacy',
                            min: 10,
                            max: 160,
                            step: 5,
                            placeholder: '10–160',
                            required: true
                        },
                        {
                            key: 'comprehension',
                            label: 'Comprehension',
                            min: 10,
                            max: 160,
                            step: 5,
                            placeholder: '10–160',
                            required: true
                        },
                        {
                            key: 'conversation',
                            label: 'Conversation',
                            min: 10,
                            max: 160,
                            step: 5,
                            placeholder: '10–160',
                            required: true
                        },
                        {
                            key: 'production',
                            label: 'Production',
                            min: 10,
                            max: 160,
                            step: 5,
                            placeholder: '10–160',
                            required: true
                        }
                    ]
                };

                return fields[examName] || [];
            }
        };
    }
</script>
