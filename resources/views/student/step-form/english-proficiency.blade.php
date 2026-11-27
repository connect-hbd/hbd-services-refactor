{{-- Step 3 — English Proficiency (kept separate from the long academic form) --}}
<form action="{{ route('student.english-proficiency.update') }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    @include('student.step-form.englishProficiencyForm', [
        'englishProficiency' => $englishProficiency ?? null,
    ])

    {{-- Footer --}}
    <div
        class="flex items-center justify-between gap-3 px-5 sm:px-8 py-4 bg-neutral-50/50 dark:bg-neutral-800/30 border border-neutral-200/80 dark:border-neutral-800 sm:rounded-2xl">
        <button type="button" @click="$dispatch('go-to-step', 2)"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
            <iconify-icon icon="lucide:arrow-left" class="text-sm"></iconify-icon>
            Back
        </button>

        <button type="submit"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-brand-600 text-white text-sm font-bold shadow-xs hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:scale-[0.98] transition-all">
            <iconify-icon icon="lucide:check" class="text-sm"></iconify-icon>
            Save &amp; Continue
        </button>
    </div>
</form>
