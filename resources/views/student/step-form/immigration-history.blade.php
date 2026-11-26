
{{-- Immigration History --}}
@php
    $initialRefusals = [];

    if ($immigrationRefusals->count()) {
        foreach ($immigrationRefusals as $i => $ir) {
            $initialRefusals[] = [
                'id' => $ir->id,
                'country' => old("refusals.{$i}.country", $ir->country ?? ''),
                'refusal_date' => old("refusals.{$i}.refusal_date", $ir->refusal_date?->format('Y-m-d') ?? ''),
                'grounds' => old("refusals.{$i}.grounds", $ir->grounds ?? ''),
                'document_name' => $ir->document_name ?? '',
                'existingDoc' => $ir->document_name ?? '',
            ];
        }
    } elseif (old('refusals')) {
        foreach (old('refusals') as $r) {
            $initialRefusals[] = array_merge([
                'id' => null,
                'country' => '',
                'refusal_date' => '',
                'grounds' => '',
                'document_name' => '',
                'existingDoc' => '',
            ], $r);
        }
    }

    $initialImmigration = [
        'refusals' => $initialRefusals,
    ];
@endphp

<div
    x-data="immigrationForm({{ Js::from($initialImmigration) }})"
    x-init="init()"
    class="overflow-hidden border border-neutral-200/80 bg-white shadow-xs sm:rounded-2xl dark:border-neutral-800 dark:bg-neutral-900"
>

    {{-- Header --}}
    <div class="flex items-center gap-3 border-b border-neutral-100 px-4 py-5 sm:px-6 dark:border-neutral-800">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">
            <iconify-icon icon="lucide:shield-alert" class="text-xl"></iconify-icon>
        </div>

        <div>
            <h3 class="text-base font-bold text-neutral-900 dark:text-white">
                Immigration History Setup
            </h3>

            <p class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                Record previous visa refusals and immigration history.
            </p>
        </div>

    </div>

    <form
        action="{{ route('student.account.immigration-history.update') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

        <div class="space-y-5 px-4 pt-5 sm:px-8">

            {{-- Section Header --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-3 dark:border-neutral-800">

                <div class="flex items-center gap-2">
                    <iconify-icon icon="lucide:history" class="text-base text-amber-500"></iconify-icon>

                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 dark:text-neutral-200">
                        Visa Refusal Records
                    </h4>

                    <span class="rounded-full bg-neutral-100 px-2 py-0.5 text-[11px] font-semibold text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                        x-text="refusals.length">
                    </span>
                </div>

                <button
                    type="button"
                    @click="addRefusal()"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-400"
                >
                    <iconify-icon icon="lucide:plus"></iconify-icon>
                    Add Refusal
                </button>

            </div>

            @error('refusals')
                <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror

            {{-- Refusal Records --}}
            <div class="space-y-4">

                <template x-for="(record, index) in refusals" :key="record.id || record.uid">

                    <div class="space-y-4 rounded-xl border border-amber-200/80 bg-amber-50/30 p-4 sm:p-5 dark:border-amber-900/40 dark:bg-amber-950/10">

                        {{-- Hidden Fields --}}
                        <input
                            type="hidden"
                            :name="`refusals[${index}][id]`"
                            :value="record.id || ''"
                        >

                        <input
                            type="hidden"
                            :name="`refusals[${index}][sort_order]`"
                            :value="index"
                        >

                        {{-- Card Header --}}
                        <div class="flex items-center justify-between gap-3 border-b border-amber-200/60 pb-3 dark:border-amber-900/30">

                            <div class="flex items-center gap-2.5">

                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300">
                                    <iconify-icon icon="lucide:file-warning"></iconify-icon>
                                </div>

                                <div>
                                    <h5 class="text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        x-text="record.country || 'Refusal Record ' + (index + 1)">
                                    </h5>

                                    <p class="text-[10px] text-neutral-500">
                                        Previous Visa Refusal
                                    </p>
                                </div>

                            </div>

                            <button
                                type="button"
                                x-show="refusals.length > 1"
                                @click="removeRefusal(index)"
                                class="inline-flex items-center gap-1 rounded-lg px-2 py-1.5 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30"
                            >
                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                Remove
                            </button>

                        </div>

                        {{-- Fields --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            {{-- Country --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Country <span class="text-rose-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    :name="`refusals[${index}][country]`"
                                    x-model="record.country"
                                    required
                                    placeholder="e.g. Canada"
                                    class="h-10 w-full rounded-lg border border-neutral-200 bg-white px-3.5 text-sm text-neutral-800 outline-none transition placeholder:text-neutral-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"
                                >
                            </div>

                            {{-- Refusal Date --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Refusal Date
                                </label>

                                <input
                                    type="date"
                                    :name="`refusals[${index}][refusal_date]`"
                                    x-model="record.refusal_date"
                                    class="h-10 w-full rounded-lg border border-neutral-200 bg-white px-3.5 text-sm text-neutral-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"
                                >
                            </div>

                            {{-- Grounds --}}
                            <div class="sm:col-span-2">

                                <label class="mb-1.5 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Grounds for Refusal
                                </label>

                                <textarea
                                    :name="`refusals[${index}][grounds]`"
                                    x-model="record.grounds"
                                    rows="3"
                                    placeholder="Explain the reason for visa refusal..."
                                    class="w-full resize-y rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-sm text-neutral-800 outline-none transition placeholder:text-neutral-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"
                                ></textarea>

                            </div>

                            {{-- Document Upload --}}
                            <div class="sm:col-span-2">

                                <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Refusal Letter / Supporting Document
                                </label>

                                <div class="flex flex-wrap items-center gap-3">

                                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">

                                        <iconify-icon icon="lucide:upload-cloud" class="text-base text-amber-600"></iconify-icon>

                                        Choose File

                                        <input
                                            type="file"
                                            :name="`refusals[${index}][document]`"
                                            accept=".pdf,.png,.jpg,.jpeg"
                                            class="hidden"
                                            @change="handleFile($event, index)"
                                        >
                                    </label>

                                    {{-- Selected / Existing File --}}
                                    <template x-if="record.document_name">

                                        <div class="flex max-w-full items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-xs dark:border-neutral-700 dark:bg-neutral-900">

                                            <iconify-icon icon="lucide:file-text" class="shrink-0 text-amber-600"></iconify-icon>

                                            <span class="max-w-[180px] truncate font-medium text-neutral-700 dark:text-neutral-300"
                                                x-text="record.document_name">
                                            </span>

                                        </div>

                                    </template>

                                    <template x-if="!record.document_name">
                                        <span class="text-xs text-neutral-400">
                                            PDF, JPG or PNG
                                        </span>
                                    </template>

                                </div>

                                {{-- Existing File Indicator --}}
                                <p
                                    x-show="record.existingDoc"
                                    class="mt-2 text-[11px] text-emerald-600 dark:text-emerald-400"
                                >
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    Previously uploaded document
                                </p>

                            </div>

                        </div>

                    </div>

                </template>

            </div>

            {{-- Empty Information --}}
            <div class="rounded-lg bg-neutral-50 p-3 text-xs leading-5 text-neutral-500 dark:bg-neutral-800/50">
                <iconify-icon icon="lucide:info" class="mr-1"></iconify-icon>
                If you have multiple visa refusals, add each refusal separately with its country, date and supporting document.
            </div>

        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-3 border-t border-neutral-100 bg-neutral-50/50 px-4 py-4 sm:px-8 dark:border-neutral-800 dark:bg-neutral-800/30">

            <button
                type="button"
                @click="$dispatch('go-to-step', 4)"
                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2.5 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
            >
                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                Back
            </button>

            <div class="flex items-center gap-2">

                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="rounded-lg px-3 py-2.5 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
                >
                    Discard
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700 active:scale-[0.98] sm:px-5"
                >
                    <iconify-icon icon="lucide:check"></iconify-icon>
                    Save & Continue
                </button>

            </div>

        </div>

    </form>
</div>

@push('scripts')
<script>
    function immigrationForm(initial) {
        return {
            refusals: (initial.refusals || []).map((record, index) => ({
                ...record,
                uid: record.id || `existing-${index}`
            })),

            init() {
                if (this.refusals.length === 0) {
                    this.addRefusal();
                }
            },

            addRefusal() {
                this.refusals.push({
                    id: null,
                    uid: Date.now() + Math.random(),
                    country: '',
                    refusal_date: '',
                    grounds: '',
                    document_name: '',
                    existingDoc: ''
                });
            },

            removeRefusal(index) {
                if (this.refusals.length > 1) {
                    this.refusals.splice(index, 1);
                }
            },

            handleFile(event, index) {
                const file = event.target.files[0];

                if (file) {
                    this.refusals[index].document_name = file.name;
                }
            }
        };
    }
</script>
@endpush
