@extends('backend.layouts.app')

@section('content')
    @php
        $selectedCountryId = old('country_id', $employee?->country_id);
        $selectedCityId = old('city_id', $employee?->city_id);

        $normalizeArrayInput = function ($value) {
            if (is_string($value)) {
                $value = explode(',', $value);
            }

            if (!is_array($value)) {
                return [''];
            }

            $value = array_values(
                array_filter(
                    array_map(static fn($item) => is_string($item) ? trim($item) : $item, $value),
                    static fn($item) => filled($item),
                ),
            );

            return $value ?: [''];
        };

        $languages = $normalizeArrayInput(old('languages', $employee?->languages ?? []));
        $expertise = $normalizeArrayInput(old('expertise', $employee?->expertise ?? []));
    @endphp

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950" x-data="{
        countryId: @js($selectedCountryId),
        cityId: @js($selectedCityId),
    
        cities: @js(
    $cities
        ->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'country_id' => $city->country_id,
            ];
        })
        ->values(),
),
    
        get filteredCities() {
            if (!this.countryId) {
                return [];
            }
    
            return this.cities.filter(
                city => String(city.country_id) === String(this.countryId)
            );
        },
    
        countryChanged() {
            const cityExists = this.filteredCities.some(
                city => String(city.id) === String(this.cityId)
            );
    
            if (!cityExists) {
                this.cityId = '';
            }
        }
    }" x-init="countryChanged()">
        <form action="" method="POST" enctype="multipart/form-data" class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            @csrf
            @method('PUT')


            <div class="space-y-6">
                {{-- Personal Information --}}
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            Personal Information
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Manage your personal and identification details.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

                        <x-form.input-text name="first_name" label="First Name" value=""
                            placeholder="Enter first name" />

                        <x-form.input-text name="last_name" label="Last Name" value=""
                            placeholder="Enter last name" />

                        <x-form.input-text name="date_of_birth" label="Date of Birth" type="date" value="" />

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Gender
                            </label>

                            <select name="gender"
                                class="h-11 w-full rounded-lg border border-slate-200 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Select Gender</option>
                                @foreach (['Male', 'Female', 'Other'] as $gender)
                                    <option value="{{ $gender }}">
                                        {{ $gender }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <x-form.input-text name="religion" label="Religion" value="" placeholder="Enter religion" />

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Blood Group
                            </label>

                            <select name="blood_group"
                                class="h-11 w-full rounded-lg border border-slate-200 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Select Blood Group</option>
                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}">
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-3">
                            <x-form.input-text name="national_id" label="National ID / Passport Number" value=""
                                placeholder="Enter identification number" />
                        </div>

                    </div>
                </section>
                {{-- Address Information --}}
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            Address & Location
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Manage your current address and work destination.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <x-form.textarea-input name="address" label="Full Address" rows="3" value=""
                                placeholder="Enter your complete address..." />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Work Destination
                            </label>

                            {{-- <select name="work_destination_id"
                class="h-11 w-full rounded-lg border border-slate-200 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-800 dark:text-white">

                <option value="">Select Country</option>

                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">
                        {{ $country->name }}
                    </option>
                @endforeach

            </select> --}}
                        </div>

                    </div>
                </section>

                {{-- Emergency Contact --}}
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            Emergency Contact
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Provide someone we can contact in an emergency.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                        <x-form.input-text name="emergency_contact_name" label="Contact Person Name" value=""
                            placeholder="Enter contact person name" />

                        <x-form.input-text name="emergency_contact_phone" label="Emergency Phone" value=""
                            placeholder="+8801XXXXXXXXX" />

                    </div>
                </section>

                {{-- Bio --}}
                <x-form.textarea-input name="bio" label="Bio" rows="5"
                    placeholder="Write something about yourself..." value="" />

                {{-- Languages / Expertise --}}
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

                    {{-- Languages --}}
                    <div x-data="{
                        items: @js($languages),
                    
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
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Languages
                            </label>
                            <button type="button" @click="add()"
                                class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-brand-600 transition-colors hover:bg-brand-50 hover:text-brand-700 dark:text-brand-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-300">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Language
                            </button>
                        </div>

                        <div class="space-y-2.5">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="group flex items-center gap-2">
                                    <input type="text" name="languages[]" x-model="items[index]" placeholder="English"
                                        class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-400 transition-all duration-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500 dark:focus:ring-brand-500/20">
                                    <button type="button" @click="remove(index)"
                                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 dark:border-slate-700 dark:text-slate-500 dark:hover:border-red-900/50 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        @error('languages')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('languages.*')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Expertise --}}
                    <div x-data="{
                        items: @js($expertise),
                    
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
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Expertise
                            </label>
                            <button type="button" @click="add()"
                                class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-brand-600 transition-colors hover:bg-brand-50 hover:text-brand-700 dark:text-brand-400 dark:hover:bg-brand-500/10 dark:hover:text-brand-300">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Expertise
                            </button>
                        </div>

                        <div class="space-y-2.5">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="group flex items-center gap-2">
                                    <input type="text" name="expertise[]" x-model="items[index]"
                                        placeholder="SOP Writing"
                                        class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder:text-slate-400 transition-all duration-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500 dark:focus:ring-brand-500/20">
                                    <button type="button" @click="remove(index)"
                                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all duration-200 hover:border-red-200 hover:bg-red-50 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 dark:border-slate-700 dark:text-slate-500 dark:hover:border-red-900/50 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        @error('expertise')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('expertise.*')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Bank Accounts --}}
              <section class="rounded-2xl border border-neutral-200/80 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
    x-data="{ isEditing: {{ $errors->hasAny(['bank_account_id', 'bank_name', 'bank_branch', 'account_name', 'account_number', 'routing_number', 'is_primary']) ? 'true' : 'false' }} }">

    {{-- Header --}}
    <div class="border-b border-neutral-100 bg-neutral-50/50 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-800/50 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-neutral-900 dark:text-white">
                Bank Account Information
            </h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                Manage employee bank account details, routing numbers, and primary disbursement preferences.
            </p>
        </div>

        {{-- Toggle Edit Button --}}
        <div>
            <button type="button" @click="isEditing = !isEditing"
                x-text="isEditing ? 'Cancel Edit' : 'Edit Information'"
                class="inline-flex items-center px-3.5 py-1.5 text-xs font-medium rounded-lg border border-neutral-300 bg-white text-neutral-700 shadow-sm hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 transition-all">
            </button>
        </div>
    </div>

    <form action="{{ role_route('role.update.bank', ['employee' => $employee->id]) }}" method="POST" class="space-y-6 p-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="bank_account_id" value="">

        {{-- Bank Name & Bank Branch --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-form.input-text name="bank_name" label="Bank Name"
                value=""
                placeholder="e.g. Dutch-Bangla Bank Ltd"
                ::disabled="!isEditing" />

            <x-form.input-text name="bank_branch" label="Bank Branch"
                value=""
                placeholder="e.g. Gulshan Branch"
                ::disabled="!isEditing" />
        </div>

        {{-- Account Name & Account Number --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-form.input-text name="account_name" label="Account Name"
                value=""
                placeholder="e.g. John Doe"
                ::disabled="!isEditing" />

            <x-form.input-text name="account_number" label="Account Number" type="text"
                value=""
                placeholder="e.g. 1234567890123"
                ::disabled="!isEditing" />
        </div>

        {{-- Routing Number & Primary Toggle --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 items-center">
            <x-form.input-text name="routing_number" label="Routing Number"
                value=""
                placeholder="e.g. 125261234"
                ::disabled="!isEditing" />

           
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

                {{-- Employee Documents --}}
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            My Documents
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Upload or update your identification and educational documents.
                        </p>
                    </div>

                    <div class="space-y-4 p-6">

                        @php
                            $documentTypes = [
                                'national_id' => 'National ID',
                                'passport' => 'Passport',
                                'educational_certificate' => 'Educational Certificate',
                                'experience_certificate' => 'Experience Certificate',
                                'cv' => 'Resume / CV',
                                'other' => 'Other Document',
                            ];
                        @endphp

                        @foreach ($documentTypes as $key => $label)
                            @php
                                $document = $employee?->documents?->firstWhere('document_key', $key);
                            @endphp

                            <div
                                class="flex flex-col justify-between gap-4 rounded-xl border border-slate-200 p-4 sm:flex-row sm:items-center dark:border-slate-700">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-8.5A2.25 2.25 0 0017.25 3.5H8.25L3.5 8.25v10.5A2.25 2.25 0 005.75 21h11.5a2.25 2.25 0 002.25-2.25v-4.5M8.25 3.5v4.75H3.5" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">
                                            {{ $label }}
                                        </h3>

                                        @if ($document)
                                            <p class="mt-1 truncate text-xs text-green-600">
                                                Uploaded: {{ $document->original_name }}
                                            </p>
                                        @else
                                            <p class="mt-1 text-xs text-slate-400">
                                                Not uploaded yet
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex shrink-0 items-center gap-3">

                                    @if ($document)
                                        <a href="" target="_blank"
                                            class="text-sm font-medium text-brand-600 hover:underline">
                                            View
                                        </a>
                                    @endif

                                    <input type="hidden" name="documents[{{ $key }}][document_key]"
                                        value="{{ $key }}">

                                    <label
                                        class="cursor-pointer rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">

                                        {{ $document ? 'Replace File' : 'Upload File' }}

                                        <input type="file" name="documents[{{ $key }}][file]" class="hidden"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </label>

                                </div>

                            </div>
                        @endforeach

                    </div>
                </section>

            </div>
        </form>
    </div>
@endsection
