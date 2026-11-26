{{-- Pre-populate Alpine from backend data --}}
@php
    $sp = $studyPlan;

    $initialChoices = [];
    if ($sp && $sp->choices->count()) {
        foreach ($sp->choices as $i => $c) {
            $initialChoices[] = [
                'id' => $c->id,
                'program' => old("choices.{$i}.program", $c->program ?? ''),
                'country' => old("choices.{$i}.country", $c->country ?? ''),
                'start_date' => old("choices.{$i}.start_date", $c->start_date ?? ''),
                'summary' => old("choices.{$i}.summary", $c->summary ?? ''),
            ];
        }
    } elseif (old('choices')) {
        foreach (old('choices') as $i => $c) {
            $initialChoices[] = array_merge(['id' => null], $c);
        }
    }

    $initialStudyPlan = [
        'targetDegree' => old('target_degree', $sp?->target_degree ?? ''),
        'choices' => $initialChoices,
    ];
@endphp

<div x-data="studyPlanForm({{ Js::from($initialStudyPlan) }})" x-init="init()"
    class="overflow-hidden border-neutral-200/80 bg-white shadow-xs sm:rounded-2xl sm:border dark:border-neutral-800 dark:bg-neutral-900">

    {{-- Header --}}
    <div
        class="flex flex-col gap-4 border-b border-neutral-100 px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-neutral-800">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                <iconify-icon icon="lucide:compass" class="text-xl"></iconify-icon>
            </div>
            <div>
                <h3 class="text-base font-bold tracking-tight text-neutral-900 sm:text-lg dark:text-white">
                    Study Plan & Country Preferences
                </h3>
                <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                    Select your target degree level and rank your destination and program choices.
                </p>
            </div>
        </div>

        {{-- Target Degree Dropdown (Single custom arrow) --}}
       
        <div
            class="flex items-center gap-2 rounded-xl bg-neutral-50/80 dark:bg-neutral-800/50 px-3 py-1.5 border border-neutral-200/80 dark:border-neutral-700">
            <label class="text-xs font-semibold text-neutral-600 dark:text-neutral-300 whitespace-nowrap">Target
                Degree:</label>
            <select x-model="targetDegree" name="target_degree"
                class="bg-transparent text-xs font-bold text-brand-600 dark:text-brand-400 focus:outline-none border rounded-lg cursor-pointer pr-2">
                <option value="" disabled class="bg-white dark:bg-neutral-900 text-neutral-800">Select Degree
                </option>
                <option value="Master's Degree" class="bg-white dark:bg-neutral-900 text-neutral-800">Master's Degree
                </option>
                <option value="Bachelor's Degree" class="bg-white dark:bg-neutral-900 text-neutral-800">Bachelor's
                    Degree</option>
                <option value="PhD / Doctorate" class="bg-white dark:bg-neutral-900 text-neutral-800">PhD / Doctorate
                </option>
                <option value="Postgraduate Diploma" class="bg-white dark:bg-neutral-900 text-neutral-800">Postgraduate
                    Diploma</option>
            </select>
        </div>
    </div>

    @error('target_degree')
        <p class="px-5 pt-3 text-xs font-medium text-rose-600 sm:px-8 dark:text-rose-400">{{ $message }}</p>
    @enderror

    <form action="{{ route('student.study-plan.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Single source of truth for form submission --}}
        <input type="hidden" name="target_degree" :value="targetDegree">

        <div class="space-y-5 px-4 pt-5 sm:px-8">

            {{-- Section Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-3.5 dark:border-neutral-800">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="lucide:globe" class="text-base text-brand-500"></iconify-icon>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 dark:text-neutral-200">
                        Destination & Program Choices
                    </h4>
                    <span
                        class="rounded-full bg-neutral-100 px-2.5 py-0.5 text-[11px] font-semibold text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                        x-text="choices.length + (choices.length === 1 ? ' Choice' : ' Choices')"></span>
                </div>

                <button type="button" @click="addChoice()"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-brand-200/80 bg-brand-50/70 px-3.5 py-2 text-xs font-semibold text-brand-700 transition hover:bg-brand-100/80 dark:border-brand-800/60 dark:bg-brand-950/50 dark:text-brand-300 dark:hover:bg-brand-950/80">
                    <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                    Add Preference Choice
                </button>
            </div>

            @error('choices')
                <p class="text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror

            {{-- Choice Cards --}}
            <div class="space-y-4">
                <template x-for="(choice, index) in choices" :key="choice.id || index">
                    <div
                        class="space-y-4 rounded-xl border border-neutral-200/90 bg-neutral-50/50 p-4 transition sm:p-5 dark:border-neutral-800 dark:bg-neutral-800/30">

                        {{-- Hidden form fields that submit to server --}}
                        <input type="hidden" :name="`choices[${index}][program]`" :value="choice.program">
                        <input type="hidden" :name="`choices[${index}][country]`" :value="choice.country">
                        <input type="hidden" :name="`choices[${index}][start_date]`" :value="choice.start_date">
                        <input type="hidden" :name="`choices[${index}][summary]`" :value="choice.summary">

                        {{-- Card Header --}}
                        <div
                            class="flex items-center justify-between gap-2 border-b border-neutral-200/70 pb-3 dark:border-neutral-700/70">
                            <div class="flex items-center gap-2.5">
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-100 text-xs font-bold text-brand-700 dark:bg-brand-900/60 dark:text-brand-300"
                                    x-text="index + 1"></span>
                                <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                    x-text="index === 0 ? 'Choice 1 (Primary Destination)' : 'Choice ' + (index + 1)"></span>
                            </div>

                            <button type="button" x-show="choices.length > 1" @click="removeChoice(index)"
                                class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30">
                                <iconify-icon icon="lucide:trash-2" class="text-sm"></iconify-icon>
                                Remove
                            </button>
                        </div>

                        {{-- Fields Grid --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            {{-- Intended Program --}}
                            <div>
                                <label :for="'program_' + index"
                                    class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Intended Program <span class="text-rose-500">*</span>
                                </label>
                                <input :id="'program_' + index" type="text" x-model="choice.program" required
                                    placeholder="e.g. MSc in Human-Computer Interaction"
                                    class="h-10 w-full rounded-lg border border-neutral-200 bg-white px-3.5 text-sm text-neutral-900 placeholder:text-neutral-400 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            </div>

                            {{-- Destination Country --}}
                            <div>
                                <label :for="'country_' + index"
                                    class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Destination Country <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select :id="'country_' + index" x-model="choice.country" required
                                        class="h-10 w-full cursor-pointer appearance-none bg-none rounded-lg border border-neutral-200 bg-white px-3.5 pr-10 text-sm text-neutral-900 outline-none transition [-moz-appearance:none] [-webkit-appearance:none] [&::-ms-expand]:hidden focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                        <option value="">Select Country</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Malaysia">Malaysia</option>
                                    </select>
                                    <iconify-icon icon="lucide:chevron-down"
                                        class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-base text-neutral-400"></iconify-icon>
                                </div>
                            </div>

                            {{-- Study Intake --}}
                            <div>
                                <label :for="'intake_' + index"
                                    class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Study Intake <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select :id="'intake_' + index" x-model="choice.start_date" required
                                        class="h-10 w-full cursor-pointer appearance-none bg-none rounded-lg border border-neutral-200 bg-white px-3.5 pr-10 text-sm text-neutral-900 outline-none transition [-moz-appearance:none] [-webkit-appearance:none] [&::-ms-expand]:hidden focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                        <option value="">Select Intake</option>
                                        <option value="Spring 2027">Spring 2027 (Jan – Feb)</option>
                                        <option value="Summer 2027">Summer 2027 (May – Jun)</option>
                                        <option value="Fall 2027">Fall 2027 (Sep – Oct)</option>
                                        <option value="Spring 2028">Spring 2028 (Jan – Feb)</option>
                                        <option value="Summer 2028">Summer 2028 (May – Jun)</option>
                                        <option value="Fall 2028">Fall 2028 (Sep – Oct)</option>
                                    </select>
                                    <iconify-icon icon="lucide:chevron-down"
                                        class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-base text-neutral-400"></iconify-icon>
                                </div>
                            </div>

                            {{-- Summary --}}
                            <div class="sm:col-span-2 lg:col-span-3">
                                <label :for="'summary_' + index"
                                    class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Study Plan Summary & Academic Motivation
                                </label>
                                <textarea :id="'summary_' + index" x-model="choice.summary" rows="3"
                                    placeholder="Explain your academic objective, alignment with career goals, and reasons for selecting this country and program..."
                                    class="min-h-[88px] w-full resize-y rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"></textarea>
                            </div>

                        </div>
                    </div>
                </template>
            </div>

        </div>

        {{-- Footer --}}
        <div
            class="flex items-center justify-between gap-3 border-t border-neutral-100 bg-neutral-50/70 px-4 py-4 sm:px-8 dark:border-neutral-800 dark:bg-neutral-800/30">
            <button type="button" @click="$dispatch('go-to-step', 2)"
                class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-200/60 dark:text-neutral-300 dark:hover:bg-neutral-800">
                <iconify-icon icon="lucide:arrow-left" class="text-sm"></iconify-icon>
                Back
            </button>

            <div class="flex items-center gap-2.5">            
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-xs transition hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:scale-[0.98]">
                    <iconify-icon icon="lucide:check" class="text-sm"></iconify-icon>
                    Save & Continue
                </button>
            </div>
        </div>

    </form>
</div>

@push('scripts')
    <script>
        function studyPlanForm(initial) {
            return {
                targetDegree: initial.targetDegree ?? '',
                choices: Array.isArray(initial.choices) ? initial.choices : [],

                init() {
                    if (this.choices.length === 0) {
                        this.addChoice();
                    }
                },

                addChoice() {
                    this.choices.push({
                        id: Date.now() + Math.random(),
                        program: '',
                        country: '',
                        start_date: '',
                        summary: ''
                    });
                },

                removeChoice(index) {
                    if (this.choices.length > 1) {
                        this.choices.splice(index, 1);
                    }
                },
            };
        }
    </script>
@endpush