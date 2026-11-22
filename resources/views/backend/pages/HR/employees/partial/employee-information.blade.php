@php
    $normalizeArrayInput = function ($value) {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if (! is_array($value)) {
            return [''];
        }

        $value = array_values(array_filter(
            array_map(static fn($item) => is_string($item) ? trim($item) : $item, $value),
            static fn($item) => filled($item),
        ));

        return $value ?: [''];
    };

    $languages = $normalizeArrayInput(old('languages', $employee?->languages ?? []));
    $expertise = $normalizeArrayInput(old('expertise', $employee?->expertise ?? []));
@endphp

<section class="rounded-2xl border border-neutral-200/80 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
    x-data="{ isEditing: {{ $errors->hasAny(['experience_years', 'bio', 'work_destinations', 'languages', 'expertise']) ? 'true' : 'false' }} }">

    {{-- Header --}}
    <div class="border-b border-neutral-100 bg-neutral-50/50 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-800/50 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-neutral-900 dark:text-white">
                Employee Information
            </h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                Add your professional profile, experience, languages and areas of expertise.
            </p>
        </div>

        <button type="button" @click="isEditing = !isEditing"
            x-text="isEditing ? 'Cancel Edit' : 'Edit Information'"
            class="inline-flex items-center px-3.5 py-1.5 text-xs font-medium rounded-lg border border-neutral-300 bg-white text-neutral-700 shadow-sm hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-all">
        </button>
    </div>

    <form action="{{ role_route('role.update.employee-profile', ['employee' => $employee->id]) }}" method="POST"
        class="space-y-4 p-6">
        @csrf
        @method('PUT')
            {{-- Education and Instutions --}}
         <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-form.input-text name="education" label="Last Education" type="text"
                value="{{ old('education', $employee?->education) }}"
                placeholder="e.g. BSc in Computer Science"
                x-bind:disabled="!isEditing" />

             <x-form.input-text name="institution" label="Institution" type="text"
                value="{{ old('institution', $employee?->institution) }}"
                placeholder="e.g. Dhaka University"
                x-bind:disabled="!isEditing" />
        </div>

        {{-- Experience & Destinations --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-form.input-text name="experience_years" label="Experience (Years)" type="number" min="0"
                value="{{ old('experience_years', $employee?->experience_years) }}"
                placeholder="e.g. 5"
                x-bind:disabled="!isEditing" />

            <x-form.multi-select name="work_destinations[]" label="Work Destinations"
                :options="$countries->map(fn($country) => ['id' => (string) $country->id, 'name' => $country->name])->values()"
                :selected="old('work_destinations', $employee?->work_destinations ?? [])"
                placeholder="Select destinations..." />
        </div>

        {{-- Bio --}}
        <x-form.textarea-input name="bio" label="Bio" rows="5"
            value="{{ old('bio', $employee?->bio) }}"
            placeholder="Write something about yourself..."
            x-bind:disabled="!isEditing" />

        {{-- Languages / Expertise --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            @foreach (['languages' => $languages, 'expertise' => $expertise] as $field => $items)
                @php($label = $field === 'languages' ? 'Languages' : 'Expertise')
                @php($placeholder = $field === 'languages' ? 'English' : 'SOP Writing')

                <div x-data="{
                    items: @js($items),
                    add() {
                        this.items.push('');
                    },
                    remove(index) {
                        if (this.items.length === 1) {
                            this.items = [''];
                            return;
                        }
                        this.items.splice(index, 1);
                    }
                }" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            {{ $label }}
                        </label>
                        <button type="button" @click="add()" x-show="isEditing"
                            class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-brand-600 transition-colors hover:bg-brand-50 hover:text-brand-700 dark:text-brand-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add {{ $label }}
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="group flex items-center gap-3">
                                <input type="text" name="{{ $field }}[]" x-model="items[index]"
                                    placeholder="{{ $placeholder }}"
                                    x-bind:disabled="!isEditing"
                                    class="h-10 w-full rounded-lg border border-neutral-300 bg-white px-3.5 text-sm text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none disabled:cursor-not-allowed disabled:bg-neutral-100 disabled:text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder:text-neutral-500 dark:disabled:bg-neutral-800/50">
                                <button type="button" @click="remove(index)" x-show="isEditing"
                                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-neutral-300 text-neutral-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500 focus:outline-none dark:border-neutral-700 dark:text-neutral-500 dark:hover:border-red-900/50 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    @error($field)
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error($field . '.*')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        {{-- Submit Button (Visible only when Editing) --}}
        <div x-show="isEditing" x-cloak x-transition
            class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">
            <button type="button" @click="isEditing = false"
                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg shadow-sm hover:bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700">
                Cancel
            </button>
            <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                Save Changes
            </button>
        </div>
    </form>
</section>
