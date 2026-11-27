@php
    $proficiency = $englishProficiency ?? null;

    // In the "Already Taken" state the radios are hidden and the exam cards
    // below stay editable: view, update and add more exams.
    $hideStatusRadios = $proficiency && $proficiency->status === 'yes';

    $savedExams = [];
    if ($proficiency) {
        foreach ($proficiency->exams as $exam) {
            $i = count($savedExams);
            $savedExams[] = [
                'id' => $exam->id,
                'exam_name' => (string) old("exams.{$i}.exam_name", $exam->exam_name ?? ''),
                'year' => (string) old("exams.{$i}.year", $exam->year ?? ''),
                'scores' => (array) (old("exams.{$i}.scores") ?: ($exam->scores ?? [])),
            ];
        }
    }

    if (old('exams') && $savedExams === []) {
        foreach (old('exams') as $i => $exam) {
            $savedExams[] = [
                'id' => $exam['id'] ?? null,
                'exam_name' => $exam['exam_name'] ?? '',
                'year' => (string) ($exam['year'] ?? ''),
                'scores' => (array) ($exam['scores'] ?? []),
            ];
        }
    }

    $initialEnglish = [
        'status' => old('status', $proficiency?->status ?? ''),
        'plannedExamName' => (string) old('planned_exam_name', $proficiency?->exam_name ?? ''),
        'exams' => $savedExams,
    ];
@endphp

<div x-data="englishProficiencyForm(@js($initialEnglish))" x-init="init()"
    class="space-y-6 rounded-2xl border border-neutral-200/80 bg-white p-5 shadow-xs sm:p-6 dark:border-neutral-800 dark:bg-neutral-900/60">

    @if ($hideStatusRadios)
        {{-- Keep the status explicit when the radios are hidden --}}
        <input type="hidden" name="status" value="yes">
    @else
    {{-- 1. Exam Status --}}
    <section class="space-y-4">
        <div class="flex items-center justify-between border-b border-neutral-100 pb-3.5 dark:border-neutral-800">
            <div class="flex items-center gap-2.5">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                    <iconify-icon icon="lucide:graduation-cap" class="text-base"></iconify-icon>
                </span>
                <div>
                    <h4 class="text-sm font-bold tracking-tight text-neutral-900 dark:text-white">
                        English Proficiency Status
                    </h4>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Choose whether you plan to test or already have results.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
            {{-- Option: Will Take Exam --}}
            <label class="group relative cursor-pointer">
                <input type="radio" name="status" value="will" x-model="status" class="sr-only">
                <div :class="status === 'will'
                    ?
                    'border-brand-500 bg-brand-50/50 ring-2 ring-brand-500/15 dark:border-brand-500 dark:bg-brand-950/25' :
                    'border-neutral-200 bg-white hover:border-neutral-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700'"
                    class="flex items-center gap-3.5 rounded-xl border p-4 transition-all duration-150">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                        <iconify-icon icon="lucide:calendar-clock" class="text-xl"></iconify-icon>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h5 class="text-sm font-semibold text-neutral-900 dark:text-white">
                            Will Take Exam
                        </h5>
                        <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                            Planning to sit for an upcoming test
                        </p>
                    </div>

                    <span
                        :class="status === 'will'
                            ?
                            'border-brand-600 bg-brand-600 text-white' :
                            'border-neutral-300 bg-transparent text-transparent dark:border-neutral-600'"
                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border transition-colors">
                        <iconify-icon icon="lucide:check" class="text-xs"></iconify-icon>
                    </span>
                </div>
            </label>

            {{-- Option: Already Taken --}}
            <label class="group relative cursor-pointer">
                <input type="radio" name="status" value="yes" x-model="status" class="sr-only">
                <div :class="status === 'yes'
                    ?
                    'border-brand-500 bg-brand-50/50 ring-2 ring-brand-500/15 dark:border-brand-500 dark:bg-brand-950/25' :
                    'border-neutral-200 bg-white hover:border-neutral-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700'"
                    class="flex items-center gap-3.5 rounded-xl border p-4 transition-all duration-150">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                        <iconify-icon icon="lucide:badge-check" class="text-xl"></iconify-icon>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h5 class="text-sm font-semibold text-neutral-900 dark:text-white">
                            Already Taken
                        </h5>
                        <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                            I have completed one or more exams
                        </p>
                    </div>

                    <span
                        :class="status === 'yes'
                            ?
                            'border-brand-600 bg-brand-600 text-white' :
                            'border-neutral-300 bg-transparent text-transparent dark:border-neutral-600'"
                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border transition-colors">
                        <iconify-icon icon="lucide:check" class="text-xs"></iconify-icon>
                    </span>
                </div>
            </label>
        </div>
    </section>
    @endif

    {{-- 2. Planned Exam --}}
    <section x-show="status === 'will'" x-cloak x-transition.opacity.duration.150ms
        class="space-y-4 rounded-xl border border-blue-200/80 bg-blue-50/40 p-4 sm:p-5 dark:border-blue-900/60 dark:bg-blue-950/15">

        <div class="flex items-center gap-2">
            <iconify-icon icon="lucide:calendar-check" class="text-lg text-blue-600 dark:text-blue-400"></iconify-icon>
            <h4 class="text-sm font-bold text-neutral-900 dark:text-white">
                Planned Examination
            </h4>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="planned_exam_name"
                    class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                    Exam Name <span class="text-rose-500">*</span>
                </label>
                <select id="planned_exam_name" name="planned_exam_name" x-model="plannedExamName"
                    :required="status === 'will'"
                    class="w-full rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
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
                <x-form.form-date id="exam_date" name="exam_date" label="Expected Exam Date" placeholder="YYYY-MM-DD"
                    :value="old('exam_date', $proficiency?->exam_date?->format('Y-m-d'))" />
            </div>
        </div>
    </section>

    {{-- 3. Completed Exams --}}
    <section x-show="status === 'yes'" x-cloak x-transition.opacity.duration.150ms class="space-y-4">
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-3 dark:border-neutral-800">
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:file-check-2" class="text-lg text-brand-500"></iconify-icon>
                <h4 class="text-sm font-bold text-neutral-900 dark:text-white">
                    Completed Examination Details
                </h4>
            </div>

            <span
                class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                <span x-text="exams.length"></span>
                <span x-text="exams.length === 1 ? 'Exam' : 'Exams'"></span>
            </span>
        </div>

        <div class="space-y-4">
            <template x-for="(exam, index) in exams" :key="exam.id">
                <div
                    class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-2xs dark:border-neutral-800 dark:bg-neutral-900">

                    {{-- Exam Header --}}
                    <div
                        class="flex items-center justify-between gap-3 border-b border-neutral-100 bg-neutral-50/80 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-800/40">
                        <div class="flex items-center gap-2.5">
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-100 text-xs font-bold text-brand-700 dark:bg-brand-950 dark:text-brand-400"
                                x-text="index + 1"></span>
                            <h5 class="text-sm font-bold text-neutral-800 dark:text-white"
                                x-text="'Exam ' + (index + 1)"></h5>
                        </div>

                        <button type="button" x-show="exams.length > 1" @click="removeExam(index)"
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30">
                            <iconify-icon icon="lucide:trash-2" class="text-sm"></iconify-icon>
                            Remove
                        </button>
                    </div>

                    <div class="space-y-5 p-4 sm:p-5">
                        {{-- Existing exam id lets the controller update in place --}}
                        <input type="hidden" :name="'exams[' + index + '][id]'"
                            :value="Number.isInteger(exam.id) ? exam.id : ''">

                        {{-- Exam Name and Year --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                    Exam Name <span class="text-rose-500">*</span>
                                </label>
                                <select x-model="exam.exam_name" :name="'exams[' + index + '][exam_name]'"
                                    :required="status === 'yes'"
                                    class="w-full rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white">
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
                                <label
                                    class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                    Exam Year <span class="text-rose-500">*</span>
                                </label>
                                <select x-model="exam.year" :name="'exams[' + index + '][year]'"
                                    :required="status === 'yes'"
                                    class="w-full rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white">
                                    <option value="">Select Year</option>
                                    <template x-for="year in years" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- Dynamic Score Fields --}}
                        <div x-show="exam.exam_name" x-cloak
                            class="space-y-3 border-t border-neutral-100 pt-4 dark:border-neutral-800">
                            <div class="flex items-center justify-between">
                                <h6
                                    class="text-xs font-bold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                                    Examination Scores
                                </h6>
                                <span
                                    class="rounded-md bg-neutral-100 px-2 py-0.5 text-[11px] font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                                    x-text="exam.exam_name"></span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5">
                                <template x-for="field in scoreFields(exam.exam_name)" :key="field.key">
                                    <div
                                        :class="field.key === 'overall' || field.key === 'total' ?
                                            'rounded-xl bg-brand-50/40 p-2 ring-1 ring-brand-200/80 dark:bg-brand-950/20 dark:ring-brand-800/60' :
                                            ''">
                                        <label class="mb-1.5 block text-xs font-semibold"
                                            :class="field.key === 'overall' || field.key === 'total' ?
                                                'text-brand-700 dark:text-brand-400' :
                                                'text-neutral-600 dark:text-neutral-400'"
                                            x-text="field.label">
                                        </label>

                                        <input type="number" :step="field.step || 'any'" :min="field.min ?? ''"
                                            :max="field.max ?? ''" :placeholder="field.placeholder"
                                            x-model="exam.scores[field.key]"
                                            :name="'exams[' + index + '][scores][' + field.key + ']'"
                                            :required="status === 'yes' && field.required" inputmode="decimal"
                                            class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-semibold text-neutral-900 placeholder:font-normal placeholder:text-neutral-400 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white">
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Empty Exam Prompt --}}
                        <div x-show="!exam.exam_name"
                            class="flex items-center gap-2 rounded-xl border border-dashed border-neutral-200 bg-neutral-50/70 p-3.5 text-xs text-neutral-500 dark:border-neutral-800 dark:bg-neutral-800/40 dark:text-neutral-400">
                            <iconify-icon icon="lucide:info" class="shrink-0 text-sm text-neutral-400"></iconify-icon>
                            <span>Select an exam name above to display its section and overall score fields.</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Add Another Exam --}}
        <button type="button" @click="addExam()"
            class="flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-brand-300 bg-brand-50/30 px-4 py-3 text-sm font-semibold text-brand-600 transition hover:border-brand-400 hover:bg-brand-50/70 dark:border-brand-800 dark:bg-brand-950/15 dark:text-brand-400 dark:hover:bg-brand-950/30">
            <iconify-icon icon="lucide:plus-circle" class="text-lg"></iconify-icon>
            Add Another Exam
        </button>
    </section>

</div>

<script>
    function englishProficiencyForm(initial = {}) {
        return {
            status: initial.status || '',

            plannedExamName: initial.plannedExamName || '',

            exams: Array.isArray(initial.exams) ? initial.exams : [],

            years: Array.from({
                    length: 15
                },
                (_, i) => new Date().getFullYear() - i
            ),

            init() {
                if (this.status === 'yes' && this.exams.length === 0) {
                    this.addExam();
                }

                this.$watch('status', (value) => {
                    if (value === 'yes' && this.exams.length === 0) {
                        this.addExam();
                    }
                });
            },

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
