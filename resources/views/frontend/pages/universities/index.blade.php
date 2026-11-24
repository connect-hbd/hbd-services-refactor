@extends('frontend.layouts.app')

@section('content')


    {{-- Hero Section --}}
    <section class="relative isolate overflow-hidden bg-brand-950">

        <div class="absolute inset-0 -z-20 bg-cover bg-center opacity-20"
            style="background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=2000&q=85')">
        </div>

        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-brand-950 via-brand-900/95 to-brand-700/90"></div>

        <div class="absolute -right-40 top-0 -z-10 h-[500px] w-[500px] rounded-full bg-brand-500/25 blur-[120px]"></div>

        <div class="absolute -bottom-40 -left-40 -z-10 h-[400px] w-[400px] rounded-full bg-accent-two-500/10 blur-[100px]">
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-28 pt-16 sm:px-6 sm:pb-36 sm:pt-20 lg:px-8 lg:pb-40 lg:pt-24">

            <div class="mx-auto max-w-4xl text-center">

                <div
                    class="mb-7 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur-md">
                    <iconify-icon icon="lucide:graduation-cap" class="text-base text-accent-two-500"></iconify-icon>
                    <span class="text-xs font-semibold tracking-[0.12em] text-brand-100">
                        DISCOVER YOUR FUTURE UNIVERSITY
                    </span>
                </div>

                <h1 class="text-3xl font-bold leading-[1.15] tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Find Your University.
                    <span class="text-accent-two-500">Shape Your Future.</span>
                </h1>

                <p class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-brand-100/80 sm:text-lg sm:leading-8 mb-6 sm:mb-0">
                    Explore leading universities around the world. Discover academic
                    opportunities and find the right institution for your ambitions.
                </p>
                <div
                    class="hidden border-t border-white/10 pt-6 mt-6 flex-wrap items-center justify-center gap-x-8 gap-y-4 text-xs font-medium text-brand-100/80 sm:flex sm:text-sm">

                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:globe-2" class="text-base text-accent-two-500"></iconify-icon>
                        Global Universities
                    </div>

                    <div class="hidden h-4 w-px bg-white/20 sm:block"></div>

                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:book-open" class="text-base text-accent-two-500"></iconify-icon>
                        Diverse Study Programs
                    </div>

                    <div class="hidden h-4 w-px bg-white/20 sm:block"></div>

                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:award" class="text-base text-accent-two-500"></iconify-icon>
                        Academic Excellence
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- University Explorer --}}
    <section id="universities-list" x-data="universityExplorer(@js($universities))"
        class="relative mx-auto -mt-30 max-w-7xl scroll-mt-6 px-4 pb-20 sm:px-6 lg:px-8">

        {{-- Filters --}}
        <div class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-[0_12px_40px_rgba(6,29,52,0.07)] sm:p-6">
            <div class="flex flex-col gap-6">

                {{-- Top Header & Quick Actions Toolbar --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                <iconify-icon icon="lucide:graduation-cap" class="text-xl"></iconify-icon>
                            </div>
                            <h2 class="text-lg font-bold tracking-tight text-neutral-900">
                                Find Your University
                            </h2>
                        </div>
                        <p class="mt-1 text-xs text-neutral-500">
                            Explore and compare top-rated universities worldwide.
                        </p>
                    </div>

                    {{-- Toolbar: Sort & Filter Toggle Button --}}
                    <div class="flex items-center gap-2.5">
                        {{-- Sort Segmented Control --}}
                        <div class="inline-flex rounded-xl bg-neutral-100 p-1 text-xs font-medium text-neutral-600">
                            <button type="button" @click="sort = 'rating'"
                                :class="sort === 'rating' ? 'bg-white text-neutral-900 shadow-sm' :
                                    'text-neutral-500 hover:text-neutral-900'"
                                class="rounded-lg px-3 py-1.5 transition-all">
                                Top Rated
                            </button>
                            <button type="button" @click="sort = 'popular'"
                                :class="sort === 'popular' ? 'bg-white text-neutral-900 shadow-sm' :
                                    'text-neutral-500 hover:text-neutral-900'"
                                class="rounded-lg px-3 py-1.5 transition-all">
                                Most Popular
                            </button>
                        </div>

                        {{-- Filter Button to Toggle Tab Menu --}}
                        <button type="button" @click="showFilters = !showFilters"
                            :class="showFilters ? 'bg-brand-500 text-white border-brand-500' :
                                'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'"
                            class="inline-flex items-center gap-2 rounded-xl border px-3.5 py-2 text-xs font-medium transition-all shadow-xs">
                            <iconify-icon icon="lucide:sliders-horizontal" class="text-sm"></iconify-icon>
                            <span>Filters</span>
                            <span x-show="country" class="flex h-2 w-2 rounded-full bg-white/90"></span>
                        </button>
                    </div>
                </div>

                {{-- Live Search --}}
                <div class="w-full">
                    <div
                        class="flex flex-col gap-2.5 rounded-2xl border border-neutral-200 bg-neutral-50/50 p-2 sm:flex-row sm:items-center sm:rounded-full sm:bg-white sm:shadow-sm">
                        <div class="flex min-w-0 flex-1 items-center gap-3 px-3 py-1">
                            <iconify-icon icon="lucide:search" class="shrink-0 text-lg text-neutral-400"></iconify-icon>

                            <input type="search" x-model="search" placeholder="Search university, country or city..."
                                aria-label="Search universities" autocomplete="off"
                                class="w-full min-w-0 appearance-none border-0 bg-transparent py-2 text-sm text-neutral-900 placeholder:text-neutral-400 focus:ring-0 focus:outline-none [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden [&::-webkit-search-results-button]:hidden [&::-webkit-search-results-decoration]:hidden">

                            <button type="button" x-show="search" x-cloak @click="search = ''" aria-label="Clear search"
                                class="shrink-0 rounded-full p-1.5 text-neutral-400 transition hover:bg-neutral-100 hover:text-neutral-700">
                                <iconify-icon icon="lucide:x" class="text-base"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <p class="mt-2 px-3 text-xs text-neutral-400">
                        Results update automatically as you type.
                    </p>
                </div>

                {{-- Expandable Tab Menu (Toggled by Filter Button) --}}
                <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                    class="border-t border-neutral-100 pt-5" style="display: none;">

                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">
                            Filter by Country
                        </span>

                        <template x-if="country">
                            <button type="button" @click="country = ''"
                                class="text-xs font-medium text-brand-600 hover:text-brand-800 transition-colors">
                                Reset Country
                            </button>
                        </template>
                    </div>

                    {{-- Scrollable Country Pills / Tabs --}}
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-none">
                        <button type="button" @click="country = ''"
                            :class="country === '' ? 'bg-neutral-900 text-white border-neutral-900' :
                                'border-neutral-200 bg-white text-neutral-600 hover:border-neutral-300'"
                            class="shrink-0 rounded-xl border px-4 py-2 text-xs font-medium transition-all shadow-2xs">
                            All Countries
                        </button>

                        @foreach ($countries as $item)
                            <button type="button" @click="country = @js($item)"
                                :class="country === @js($item) ?
                                    'bg-neutral-900 text-white border-neutral-900' :
                                    'border-neutral-200 bg-white text-neutral-600 hover:border-neutral-300'"
                                class="shrink-0 rounded-xl border px-4 py-2 text-xs font-medium transition-all shadow-2xs">
                                {{ $item }}
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>




        {{-- Results Heading --}}
        <div class="mb-6 mt-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">
                    <span class="h-2 w-2 rounded-full bg-accent-two-500"></span>
                    EXPLORE THE WORLD
                </div>

                <h2 class="mt-2 text-2xl font-bold tracking-tight text-brand-950 sm:text-3xl">
                    Featured Universities
                </h2>

                <p class="mt-2 text-sm text-neutral-500">
                    Discover institutions that match your academic goals.
                </p>
            </div>

            <p class="text-xs font-medium text-neutral-500">
                Showing
                <span x-text="visibleUniversities.length" class="font-semibold text-brand-950"></span>
                of
                <span x-text="filteredUniversities.length" class="font-semibold text-brand-950"></span>
                universities
            </p>
        </div>


        {{-- University Cards --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

            <template x-for="university in visibleUniversities" :key="university.id">

               @include('frontend.pages.universities.card', ['university' => [], 'alpine' => true])
            </template>

        </div>


        {{-- Empty State --}}
        <div x-show="filteredUniversities.length === 0" x-cloak
            class="rounded-2xl border border-dashed border-neutral-300 bg-white px-5 py-16 text-center">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-500">
                <iconify-icon icon="lucide:search-x" class="text-3xl"></iconify-icon>
            </div>

            <h3 class="mt-5 text-lg font-bold text-brand-950">
                No Universities Found
            </h3>

            <p class="mt-2 text-sm text-neutral-500">
                We couldn't find any university matching your search. Try another name or country.
            </p>

            <button type="button" @click="resetFilters()"
                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-700">
                <iconify-icon icon="lucide:refresh-ccw"></iconify-icon>
                Reset Filters
            </button>

        </div>


        {{-- Load More --}}
        <div x-show="visibleUniversities.length < filteredUniversities.length" class="mt-10 text-center">

            <button type="button" @click="loadMore()"
                class="inline-flex items-center gap-2 rounded-xl border border-brand-200 bg-white px-7 py-3.5 text-sm font-semibold text-brand-600 shadow-sm transition hover:border-brand-500 hover:bg-brand-50">
                Load More Universities
                <iconify-icon icon="lucide:chevron-down"></iconify-icon>
            </button>

        </div>

    </section>
@endsection
{{-- Alpine.js Component --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('universityExplorer', (universities = []) => ({
            universities: Array.isArray(universities) ? universities : [],

            search: '',
            country: '',
            sort: 'rating',
            limit: 6,
            perPage: 3,
            showFilters: false,

            init() {
                // Whenever a filter changes, return to the first page of results.
                this.$watch('search', () => {
                    this.limit = 6;
                });

                this.$watch('country', () => {
                    this.limit = 6;
                });

                this.$watch('sort', () => {
                    this.limit = 6;
                });
            },

            get filteredUniversities() {
                const keyword = this.search.trim().toLocaleLowerCase();

                let results = this.universities.filter((university) => {
                    const matchesSearch = !keyword || [
                        university.name,
                        university.country,
                        university.city,
                        university.ranking,
                        university.type,
                        university.description
                    ].some((value) =>
                        String(value ?? '').toLocaleLowerCase().includes(keyword)
                    );

                    const matchesCountry = !this.country || university.country === this
                        .country;

                    return matchesSearch && matchesCountry;
                });

                // Always sort a fresh array; do not mutate the original dataset.
                results = [...results].sort((a, b) => {
                    if (this.sort === 'popular') {
                        return Number(b.popular ?? 0) - Number(a.popular ?? 0) ||
                            Number(b.rating ?? 0) - Number(a.rating ?? 0);
                    }

                    // Default: highest-rated universities first.
                    return Number(b.rating ?? 0) - Number(a.rating ?? 0) ||
                        Number(b.reviews ?? 0) - Number(a.reviews ?? 0);
                });

                return results;
            },

            get visibleUniversities() {
                return this.filteredUniversities.slice(0, this.limit);
            },

            get hasMore() {
                return this.visibleUniversities.length < this.filteredUniversities.length;
            },

            loadMore() {
                this.limit += this.perPage;
            },

            resetFilters() {
                this.search = '';
                this.country = '';
                this.sort = 'rating';
                this.limit = 6;
                this.showFilters = false;
            }
        }));
    });
</script>
