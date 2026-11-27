{{-- Pre-populate Alpine from backend data --}}
@php
    $initialRoles = [];

    if ($workExperiences->count()) {
        foreach ($workExperiences as $i => $we) {
            $initialRoles[] = [
                'id' => $we->id,
                'title' => old("roles.{$i}.title", $we->title ?? ''),
                'organization' => old("roles.{$i}.organization", $we->organization ?? ''),
                'startDate' => old("roles.{$i}.start_date", $we->start_date ?? ''),
                'endDate' => old("roles.{$i}.end_date", $we->end_date ?? ''),
                'isCurrent' => (bool) old("roles.{$i}.is_current", $we->is_current ?? false),
                'description' => old("roles.{$i}.description", $we->description ?? ''),
            ];
        }
    } elseif (old('roles')) {
        foreach (old('roles') as $i => $r) {
            $initialRoles[] = [
                'id' => $r['id'] ?? null,
                'title' => $r['title'] ?? '',
                'organization' => $r['organization'] ?? '',
                'startDate' => $r['start_date'] ?? '',
                'endDate' => $r['end_date'] ?? '',
                'isCurrent' => (bool) ($r['is_current'] ?? false),
                'description' => $r['description'] ?? '',
            ];
        }
    }

    $initialStatus = old('status', count($initialRoles) > 0 ? 'yes' : 'no');

    // When experience data exists the status radios are hidden and the
    // entries below stay editable: view, update and add more roles.
    $hideStatusRadios = $initialStatus === 'yes' && count($initialRoles) > 0;

    $initialWorkExperience = [
        'status' => $initialStatus,
        'roles' => $initialRoles,
    ];
@endphp

<div x-data="workExperienceForm({{ Js::from($initialWorkExperience) }})" x-init="init()"
    class="bg-white dark:bg-neutral-900 sm:rounded-2xl shadow-xs sm:border border-neutral-200/80 dark:border-neutral-800 overflow-hidden">

    {{-- Header --}}
    <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-neutral-100 dark:border-neutral-800 py-5 mb-5 px-4 sm:px-6">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                <iconify-icon icon="lucide:briefcase" class="text-xl"></iconify-icon>
            </div>
            <div>
                <h3 class="text-lg font-bold text-neutral-900 dark:text-white">Experience & Achievements Setup</h3>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">Add or update your work experience</p>
            </div>
        </div>
    </div>

    <form action="{{ route('student.work-experience.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        @if (! $hideStatusRadios)
        {{-- Work Experience Status Selection --}}
        <div class="px-5 sm:px-8 space-y-6 mb-6">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-800">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="lucide:layers" class="text-brand-500 text-base"></iconify-icon>
                    <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-wider dark:text-white">
                        Have Work Experience ?
                    </h4>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                {{-- Option: Have No Work Experience --}}
                <label class="group relative cursor-pointer">
                    <input type="radio" name="status" value="no" x-model="status" class="sr-only">
                    <div :class="status === 'no'
                        ? 'border-brand-500 bg-brand-50/50 ring-2 ring-brand-500/15 dark:border-brand-500 dark:bg-brand-950/25'
                        : 'border-neutral-200 bg-white hover:border-neutral-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700'"
                        class="flex items-center gap-3.5 rounded-xl border p-4 transition-all duration-150">

                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                            <iconify-icon icon="lucide:calendar-clock" class="text-xl"></iconify-icon>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h5 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                No Work Experience
                            </h5>
                            <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                Don't have any work experience
                            </p>
                        </div>

                        <span
                            :class="status === 'no'
                                ? 'border-brand-600 bg-brand-600 text-white'
                                : 'border-neutral-300 bg-transparent text-transparent dark:border-neutral-600'"
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border transition-colors">
                            <iconify-icon icon="lucide:check" class="text-xs"></iconify-icon>
                        </span>
                    </div>
                </label>

                {{-- Option: Have Work Experience --}}
                <label class="group relative cursor-pointer">
                    <input type="radio" name="status" value="yes" x-model="status" class="sr-only">
                    <div :class="status === 'yes'
                        ? 'border-brand-500 bg-brand-50/50 ring-2 ring-brand-500/15 dark:border-brand-500 dark:bg-brand-950/25'
                        : 'border-neutral-200 bg-white hover:border-neutral-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700'"
                        class="flex items-center gap-3.5 rounded-xl border p-4 transition-all duration-150">

                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                            <iconify-icon icon="lucide:badge-check" class="text-xl"></iconify-icon>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h5 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                Have Work Experience
                            </h5>
                            <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                                I have work experience
                            </p>
                        </div>

                        <span
                            :class="status === 'yes'
                                ? 'border-brand-600 bg-brand-600 text-white'
                                : 'border-neutral-300 bg-transparent text-transparent dark:border-neutral-600'"
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border transition-colors">
                            <iconify-icon icon="lucide:check" class="text-xs"></iconify-icon>
                        </span>
                    </div>
                </label>
            </div>
        </div>
        @else
            {{-- Keep the status explicit when the radios are hidden --}}
            <input type="hidden" name="status" value="yes">
        @endif

        {{-- Conditional Work Experience Section (Shown only when status === 'yes') --}}
        <div x-show="status === 'yes'" x-cloak x-transition class="px-5 sm:px-8 space-y-6 mb-6">
           <div class="flex items-center justify-between border-b border-neutral-100 pb-3 dark:border-neutral-800">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="lucide:layers" class="text-brand-500 text-base"></iconify-icon>
                    <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-wider dark:text-white">Work &
                        Experience</h4>
                </div>
                <button type="button" @click="addRole()"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 dark:bg-brand-950/50 px-3 py-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400 hover:bg-brand-100 transition-colors">
                    <iconify-icon icon="lucide:plus" class="text-sm"></iconify-icon>
                    Add New Experience
                </button>
            </div>

            @error('roles')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror

            <div class="space-y-4">
                <template x-for="(role, index) in roles" :key="role.id ?? index">
                    <div
                        class="p-4 sm:p-5 rounded-xl border border-neutral-200/80 dark:border-neutral-800 bg-neutral-50/40 dark:bg-neutral-800/30 space-y-4 relative">

                        {{-- Hidden server-side inputs --}}
                        <input type="hidden" :name="`roles[${index}][id]`"
                            :value="Number.isInteger(role.id) ? role.id : ''">
                        <input type="hidden" :name="`roles[${index}][title]`" :value="role.title">
                        <input type="hidden" :name="`roles[${index}][organization]`" :value="role.organization">
                        <input type="hidden" :name="`roles[${index}][start_date]`" :value="role.startDate">
                        <input type="hidden" :name="`roles[${index}][end_date]`"
                            :value="role.isCurrent ? '' : role.endDate">
                        <input type="hidden" :name="`roles[${index}][is_current]`"
                            :value="role.isCurrent ? '1' : '0'">
                        <input type="hidden" :name="`roles[${index}][description]`" :value="role.description">

                        <div
                            class="flex items-center justify-between border-b border-neutral-200/60 dark:border-neutral-700/60 pb-3">
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-900/60 text-brand-700 dark:text-brand-300 text-[11px] font-bold"
                                    x-text="index + 1"></span>
                                <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                    x-text="role.title ? role.title : 'New Position #' + (index + 1)"></span>
                            </div>
                            <button type="button" x-show="roles.length > 1" @click="removeRole(index)"
                                class="text-xs font-medium text-rose-500 hover:text-rose-700 flex items-center gap-1 transition-colors">
                                <iconify-icon icon="lucide:trash-2" class="text-sm"></iconify-icon>
                                Remove
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Role / Position Title <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="role.title" placeholder="e.g. Digital Experience Intern"
                                    class="w-full h-10 px-4 py-2.25 rounded-lg text-sm border placeholder:text-neutral-400 border-neutral-200 bg-white dark:bg-neutral-900 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            </div>

                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Organization / Company <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="role.organization"
                                    placeholder="e.g. Northfield University"
                                    class="w-full h-10 px-4 py-2.25 rounded-lg text-sm border placeholder:text-neutral-400 border-neutral-200 bg-white dark:bg-neutral-900 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            </div>

                            {{-- Start Date --}}
                            <div x-effect="
                                    const inp = $el.querySelector('input');
                                    const lbl = $el.querySelector('label');
                                    if (inp) {
                                        inp.id = 'role_start_' + index;
                                        inp.removeAttribute('name');
                                        if (inp.value !== (role.startDate || '')) inp.value = role.startDate || '';
                                    }
                                    if (lbl) lbl.setAttribute('for', 'role_start_' + index);
                                "
                                @input="if ($event.target.matches('input')) role.startDate = $event.target.value"
                                @change="if ($event.target.matches('input')) role.startDate = $event.target.value">
                                <x-form.form-date id="role_start" name="role_start" label="Start Date"
                                    placeholder="YYYY-MM-DD" :value="''" required />
                            </div>

                            {{-- End Date + Present Checkbox --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label :for="'role_end_' + index"
                                        class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        End Date
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                                        <input type="checkbox" x-model="role.isCurrent"
                                            @change="if (role.isCurrent) { role.endDate = ''; const el = document.getElementById('role_end_' + index); if (el) el.value = ''; }"
                                            class="h-4 w-4 rounded border-neutral-300 text-brand-600 focus:ring-2 focus:ring-brand-500/20 dark:border-neutral-700 dark:bg-neutral-900">
                                        <span
                                            class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Present</span>
                                    </label>
                                </div>

                                <div :class="role.isCurrent ? 'opacity-50 pointer-events-none' : ''"
                                    x-effect="
                                        const inp = $el.querySelector('input');
                                        if (inp) {
                                            inp.id = 'role_end_' + index;
                                            inp.removeAttribute('name');
                                            inp.disabled = !!role.isCurrent;
                                            const val = role.isCurrent ? '' : (role.endDate || '');
                                            if (inp.value !== val) inp.value = val;
                                        }
                                    "
                                    @input="if ($event.target.matches('input')) role.endDate = $event.target.value"
                                    @change="if ($event.target.matches('input')) role.endDate = $event.target.value">
                                    <x-form.form-date id="role_end" name="role_end" label=""
                                        placeholder="YYYY-MM-DD" :value="''" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label
                                    class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">Key
                                    Responsibilities & Accomplishments</label>
                                <textarea x-model="role.description" rows="3"
                                    placeholder="Describe main tasks, tools used, and key accomplishments..."
                                    class="w-full px-4 py-2.5 rounded-lg border placeholder:text-neutral-400 border-neutral-200 bg-white dark:bg-neutral-900 dark:border-neutral-700 text-xs font-medium text-neutral-800 dark:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all resize-y"></textarea>
                            </div>

                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div
            class="flex items-center justify-between gap-3 px-5 sm:px-8 py-4 bg-neutral-50/50 dark:bg-neutral-800/30 border-t border-neutral-100 dark:border-neutral-800">
            <button type="button" @click="$dispatch('go-to-step', 4)"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                <iconify-icon icon="lucide:arrow-left" class="text-sm"></iconify-icon>
                Back
            </button>
            <div class="flex items-center gap-3">                
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 text-white text-sm font-bold shadow-xs hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:scale-[0.98] transition-all">
                    <iconify-icon icon="lucide:check" class="text-sm"></iconify-icon>
                    Save & Continue
                </button>
            </div>
        </div>

    </form>
</div>

@push('scripts')
    <script>
        function workExperienceForm(initial) {
            return {
                status: initial.status ?? 'no',
                roles: initial.roles ?? [],

                init() {
                    if (this.roles.length === 0) {
                        this.addRole();
                    }

                    this.$watch('status', (value) => {
                        if (value === 'yes' && this.roles.length === 0) {
                            this.addRole();
                        }
                    });
                },

                addRole() {
                    this.roles.push({
                        id: 'new_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7),
                        title: '',
                        organization: '',
                        startDate: '',
                        endDate: '',
                        isCurrent: false,
                        description: ''
                    });
                },

                removeRole(index) {
                    if (this.roles.length > 1) {
                        this.roles.splice(index, 1);
                    }
                },
            };
        }
    </script>
@endpush