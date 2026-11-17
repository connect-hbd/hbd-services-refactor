@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'multiple' => false,
    'accept' => 'image/png,image/jpeg,image/webp,image/svg+xml',
    'types' => ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'],
    'hint' => 'Drag and drop your PNG, JPG, WebP, SVG images here or browse',
])

@php
    // Translate a field name such as "documents[cv]" into the dot notation
    // used by Laravel validation errors ("documents.cv").
    $errorKey = str_replace(['[]', '[', ']'], ['', '.', ''], $name);
@endphp

<div
    x-data="{
        isDragging: false,
        files: [],

        formatSize(bytes) {
            if (bytes >= 1048576) {
                return (bytes / 1048576).toFixed(2) + ' MB';
            }

            if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            }

            return bytes + ' B';
        },

        addFiles(list) {
            const incoming = Array.from(list || []);

            if (! incoming.length) {
                return;
            }

            const selected = {{ $multiple ? 'true' : 'false' }}
                ? incoming
                : incoming.slice(0, 1);

            this.files.forEach((item) => item.url && URL.revokeObjectURL(item.url));

            this.files = selected.map((file) => {
                const isImage = (file.type || '').startsWith('image/');

                return {
                    file,
                    name: file.name,
                    size: file.size,
                    isImage,
                    url: isImage ? URL.createObjectURL(file) : null,
                };
            });

            this.syncInput();
        },

        removeFile(index) {
            const [removed] = this.files.splice(index, 1);

            if (removed && removed.url) {
                URL.revokeObjectURL(removed.url);
            }

            this.syncInput();
        },

        handleDrop(event) {
            this.isDragging = false;
            this.addFiles(event.dataTransfer.files);
        },

        // Keep the native input in sync so the selected files are submitted.
        syncInput() {
            const input = this.$refs.fileInput;

            if (! input) {
                return;
            }

            const transfer = new DataTransfer();

            this.files.forEach((item) => transfer.items.add(item.file));

            input.files = transfer.files;
        },
    }"
    class="transition"
>
    <div
        @drop.prevent="handleDrop($event)"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @click="$refs.fileInput.click()"
        :class="isDragging
            ?
            'border-brand-500 bg-neutral-100 dark:bg-neutral-800' :
            'border-neutral-300 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900'"
        class="dropzone cursor-pointer rounded-xl border border-dashed p-7 transition-colors hover:border-brand-500 lg:p-10"
    >
        <!-- Hidden File Input -->
        <input
            x-ref="fileInput"
            type="file"
            name="{{ $name }}"
            @if ($multiple) multiple @endif
            @change="addFiles($event.target.files)"
            accept="{{ $accept }}"
            class="hidden"
            @click.stop
        />

        <div class="m-0 flex flex-col items-center">
            <!-- Icon Container -->
            <div class="mb-[22px] flex justify-center">
                <div
                    class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-neutral-200 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-400">
                    <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                    </svg>
                </div>
            </div>

            <!-- Text Content -->
            <h4 class="mb-3 font-semibold text-neutral-800 text-theme-xl dark:text-white/90">
                <span x-show="! isDragging">Drag & Drop Files Here</span>
                <span x-show="isDragging" x-cloak>Drop Files Here</span>
            </h4>

            <span class="mb-5 block w-full max-w-[290px] text-center text-sm text-neutral-700 dark:text-neutral-400">
                {{ $hint }}
            </span>

            <span class="font-medium underline text-theme-sm text-brand-500">
                Browse File
            </span>
        </div>
    </div>

    <!-- Selected file preview -->
    <div x-show="files.length > 0" x-cloak class="mt-4 space-y-2">
        <template x-for="(item, index) in files" :key="index">
            <div
                class="flex items-center gap-3 rounded-xl border border-neutral-200 bg-white p-3 dark:border-neutral-700 dark:bg-neutral-800/60">
                <template x-if="item.isImage">
                    <img :src="item.url" alt=""
                        class="h-12 w-12 rounded-lg border border-neutral-200 object-cover dark:border-neutral-700" />
                </template>

                <template x-if="! item.isImage">
                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-neutral-100 text-neutral-500 dark:bg-neutral-700 dark:text-neutral-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </span>
                </template>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-neutral-800 dark:text-neutral-200" x-text="item.name">
                    </p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400" x-text="formatSize(item.size)"></p>
                </div>

                <button type="button" @click.stop.prevent="removeFile(index)" aria-label="Remove file"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-neutral-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    @error($errorKey)
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
