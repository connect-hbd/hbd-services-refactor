
@extends('frontend.layouts.app')

@section('content')
 @php
    // Fixed Country Options
    $countries = ['UK', 'USA', 'Canada', 'Malaysia', 'Australia'];

    // Fixed Expertise Options
    $expertiseOptions = [
        'SOP',
        'FAC',
        'VISA',
        'SCHOLARSHIP',
        'ADMISSIONS',
    ];

    /*
    |--------------------------------------------------------------------------
    | Extract Counsellor Records
    |--------------------------------------------------------------------------
    */

    if ($counsellors instanceof \Illuminate\Pagination\AbstractPaginator) {

        // Laravel paginator
        $counsellorData = $counsellors->items();

    } elseif (is_array($counsellors) && isset($counsellors['data'])) {

        // API response as associative array
        $counsellorData = $counsellors['data'];

    } elseif (is_object($counsellors) && isset($counsellors->data)) {

        // API response as object
        $counsellorData = $counsellors->data;

    } else {

        // Direct counsellor collection
        $counsellorData = $counsellors ?? [];
    }

    // Normalize counsellor records as objects
    $counsellorList = collect($counsellorData)
        ->filter(fn ($item) => is_array($item) || is_object($item))
        ->map(function ($item) {

            $item = (object) $item;

            // Normalize nested user object
            if (isset($item->user) && is_array($item->user)) {
                $item->user = (object) $item->user;
            }

            return $item;
        })
        ->values();

    // Prepare filter data
    $counsellorFilterMap = $counsellorList
        ->map(fn ($c) => [
            'countries' => collect($c->work_destinations ?? [])
                ->filter()
                ->map(fn ($item) => trim((string) $item))
                ->values()
                ->all(),

            'expertise' => collect($c->expertise ?? [])
                ->filter()
                ->map(fn ($item) => strtoupper(trim((string) $item)))
                ->values()
                ->all(),
        ])
        ->values();
@endphp

    {{-- Hero Section --}}
    <section class="relative -mt-4 flex min-h-60 items-center overflow-hidden py-10 md:min-h-72">

        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('frontend-img/course-page-header.jpg') }}"
                alt="Expert Study & Migration Counsellors"
                class="h-full w-full object-cover"
            >

            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-900/70 to-slate-900/50"></div>
        </div>

        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">

                <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-widest text-white/90 backdrop-blur-xs">
                    Global Advisory
                </span>

                <h1 class="mt-3 text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">
                    Meet Our Expert Counsellors
                </h1>

                <p class="mt-2.5 text-sm text-slate-200 sm:text-base">
                    Connect with experienced advisors tailored to your target country and application needs.
                </p>

            </div>
        </div>
    </section>

    {{-- Counsellors Directory Section --}}
    <section
        class="py-10 sm:py-12"
        x-data="{
            selectedCountry: '',
            selectedExpertise: '',

            items: @js($counsellorFilterMap),

            resetFilters() {
                this.selectedCountry = '';
                this.selectedExpertise = '';
            },

            matchesFilter(countries, expertiseList) {

                // Country filter: match work_destinations
                const countryMatch = !this.selectedCountry ||
                    (
                        Array.isArray(countries) &&
                        countries.some(country =>
                            String(country).trim().toLowerCase() ===
                            this.selectedCountry.trim().toLowerCase()
                        )
                    );

                // Expertise filter: match expertise
                const expertiseMatch = !this.selectedExpertise ||
                    (
                        Array.isArray(expertiseList) &&
                        expertiseList.some(expertise =>
                            String(expertise).trim().toUpperCase() ===
                            this.selectedExpertise.trim().toUpperCase()
                        )
                    );

                // Both selected filters must match
                return countryMatch && expertiseMatch;
            },

            get visibleCount() {
                return this.items.filter(item =>
                    this.matchesFilter(item.countries, item.expertise)
                ).length;
            }
        }"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">

                {{-- Filter Bar --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs sm:p-5">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                        {{-- Country Filter --}}
                        <div class="min-w-0 flex-1">

                            <div class="mb-2.5 flex items-center justify-between gap-2">
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    Filter by Country
                                </span>
                            </div>

                            <div class="flex gap-2 overflow-x-auto pb-1">

                                {{-- All Countries --}}
                                <button
                                    type="button"
                                    @click="selectedCountry = ''"
                                    :class="selectedCountry === ''
                                        ? 'border-slate-900 bg-slate-900 text-white shadow-xs'
                                        : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                    class="shrink-0 rounded-xl border px-4 py-2 text-xs font-semibold transition-all cursor-pointer"
                                >
                                    All Countries
                                </button>

                                {{-- Fixed Country Buttons --}}
                                @foreach ($countries as $country)
                                    <button
                                        type="button"
                                        @click="selectedCountry = selectedCountry === @js($country) ? '' : @js($country)"
                                        :class="selectedCountry === @js($country)
                                            ? 'border-slate-900 bg-slate-900 text-white shadow-xs'
                                            : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                        class="shrink-0 rounded-xl border px-4 py-2 text-xs font-semibold transition-all cursor-pointer"
                                    >
                                        {{ $country }}
                                    </button>
                                @endforeach

                            </div>
                        </div>

                        {{-- Expertise Filter --}}
                        <div class="w-full shrink-0 sm:w-64 lg:w-60">

                            <label
                                for="expertiseFilter"
                                class="mb-2.5 block text-[11px] font-semibold uppercase tracking-wider text-slate-400"
                            >
                                Filter by Expertise
                            </label>

                            <div class="relative">
                                <select
                                    id="expertiseFilter"
                                    x-model="selectedExpertise"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2 text-xs font-medium text-slate-800 transition focus:border-slate-400 focus:bg-white focus:outline-none focus:ring-0 cursor-pointer"
                                >
                                    <option value="">All Expertise</option>

                                    @foreach ($expertiseOptions as $exp)
                                        <option value="{{ $exp }}">
                                            {{ $exp }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- Filter Result Count --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3">

                        <p class="text-xs text-slate-500">
                            Showing
                            <span
                                class="font-semibold text-slate-900"
                                x-text="visibleCount"
                            ></span>

                            of

                            <span
                                class="font-semibold text-slate-900"
                                x-text="items.length"
                            ></span>

                            counsellors
                        </p>

                        <button
                            type="button"
                            x-show="selectedCountry || selectedExpertise"
                            x-cloak
                            @click="resetFilters()"
                            class="text-xs font-semibold text-slate-600 transition hover:text-slate-900 cursor-pointer"
                        >
                            Clear Filters
                        </button>

                    </div>
                </div>

                {{-- Counsellors Grid --}}
                <div
                    x-show="items.length > 0 && visibleCount > 0"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >

                    @forelse ($counsellorList as $index => $counsellor)

                        <div
                            x-show="items[{{ $index }}] && matchesFilter(
                                items[{{ $index }}].countries,
                                items[{{ $index }}].expertise
                            )"
                            x-transition.opacity.duration.150ms
                            class="flex"
                        >
                            @include('frontend.pages.counsellors.counseller-card', [
                                'counsellor' => $counsellor,
                            ])
                        </div>

                    @empty
                    @endforelse

                </div>

                {{-- Empty State: No Counsellors Available --}}
                @if ($counsellorList->isEmpty())

                    <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center shadow-xs">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.75"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-base font-semibold text-slate-900">
                            No counsellors available right now
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                            Our advisory team profiles are currently being updated. Please check back shortly.
                        </p>

                    </div>

                @endif

                {{-- Empty State: No Matching Filter Results --}}
                <div
                    x-show="items.length > 0 && visibleCount === 0"
                    x-cloak
                    x-transition.opacity.duration.150ms
                    class="rounded-2xl border border-slate-200/80 bg-white px-6 py-14 text-center shadow-xs"
                >

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.75"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-4 text-base font-semibold text-slate-900">
                        No matching counsellors found
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">

                        We couldn't find any counsellors matching

                        <span
                            x-show="selectedCountry"
                            class="font-semibold text-slate-700"
                            x-text="selectedCountry"
                        ></span>

                        <span x-show="selectedCountry && selectedExpertise">
                            and
                        </span>

                        <span
                            x-show="selectedExpertise"
                            class="font-semibold text-slate-700"
                            x-text="selectedExpertise"
                        ></span>.

                        Try clearing your filters to see all available advisors.

                    </p>

                    <button
                        type="button"
                        @click="resetFilters()"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-slate-800 cursor-pointer"
                    >
                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>

                        Reset All Filters
                    </button>

                </div>

            </div>
        </div>
    </section>
@endsection
