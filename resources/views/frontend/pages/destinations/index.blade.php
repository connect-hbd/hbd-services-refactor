@extends('frontend.layouts.app')

@section('content')
    <section class="relative isolate overflow-hidden bg-brand-950">
        <div class="absolute inset-0 -z-20 bg-cover bg-center opacity-20"
            style="background-image:url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=2000&q=85')"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-brand-950 via-brand-900/95 to-brand-700/90"></div>
        <div class="absolute -right-40 top-0 -z-10 h-[500px] w-[500px] rounded-full bg-brand-500/25 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 -z-10 h-[400px] w-[400px] rounded-full bg-accent-two-500/10 blur-[100px]"></div>

        <div class="mx-auto max-w-7xl px-4 pb-28 pt-16 sm:px-6 sm:pb-36 sm:pt-20 lg:px-8 lg:pb-40 lg:pt-24">
            <div class="mx-auto max-w-4xl text-center">
                <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur-md">
                    <iconify-icon icon="lucide:sparkles" class="text-base text-accent-two-500"></iconify-icon>
                    <span class="text-xs font-semibold tracking-[0.12em] text-brand-100">EXPLORE YOUR FUTURE</span>
                </div>
                <h1 class="text-3xl font-bold leading-[1.15] tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Your Journey. <span class="text-accent-two-500">Your Destination.</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-brand-100/80 sm:text-lg sm:leading-8">
                    Discover world-class study destinations, leading universities and exciting opportunities.
                    Find the right place to build your future.
                </p>
                <div class="mt-8 hidden flex-wrap items-center justify-center gap-x-8 gap-y-4 border-t border-white/10 pt-6 text-xs font-medium text-brand-100/80 sm:flex sm:text-sm">
                    <span class="flex items-center gap-2"><iconify-icon icon="lucide:globe-2" class="text-accent-two-500"></iconify-icon> Global Study Options</span>
                    <span class="hidden h-4 w-px bg-white/20 sm:block"></span>
                    <span class="flex items-center gap-2"><iconify-icon icon="lucide:graduation-cap" class="text-accent-two-500"></iconify-icon> World-Class Universities</span>
                    <span class="hidden h-4 w-px bg-white/20 sm:block"></span>
                    <span class="flex items-center gap-2"><iconify-icon icon="lucide:compass" class="text-accent-two-500"></iconify-icon> Your Future Starts Here</span>
                </div>
            </div>
        </div>
    </section>

    <section
        id="destinations-list"
        x-data="destinationExplorer({
            endpoint: @js(route('destinations')),
            initialSearch: @js($search),
            initialRegion: @js($selectedRegion),
            initialSort: @js($sort),
            initialCount: @js($destinations->count()),
            total: @js($totalDestinations)
        })"
        @destination-reset.window="resetFilters()"
        class="relative mx-auto -mt-30 max-w-7xl scroll-mt-6 px-4 pb-20 sm:px-6 lg:px-8">

        <div class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-[0_12px_40px_rgba(6,29,52,0.07)] sm:p-6">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                <iconify-icon icon="lucide:globe-2" class="text-xl"></iconify-icon>
                            </div>
                            <h2 class="text-lg font-bold tracking-tight text-neutral-900">Find Your Destination</h2>
                        </div>
                        <p class="mt-1 text-xs text-neutral-500">Explore and compare study destinations around the world.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="inline-flex rounded-xl bg-neutral-100 p-1 text-xs font-medium text-neutral-600" role="group" aria-label="Sort destinations">
                            <button type="button" @click="setSort('rating')"
                                :aria-pressed="sort === 'rating'"
                                :class="sort === 'rating' ? 'bg-white text-neutral-900 shadow-sm' : 'text-neutral-500 hover:text-neutral-900'"
                                class="rounded-lg px-3 py-1.5 transition-all">Top Rated</button>
                            <button type="button" @click="setSort('popular')"
                                :aria-pressed="sort === 'popular'"
                                :class="sort === 'popular' ? 'bg-white text-neutral-900 shadow-sm' : 'text-neutral-500 hover:text-neutral-900'"
                                class="rounded-lg px-3 py-1.5 transition-all">Most Popular</button>
                        </div>

                        <button type="button" @click="showFilters = !showFilters"
                            :aria-expanded="showFilters"
                            :class="showFilters ? 'bg-brand-500 text-white border-brand-500' : 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'"
                            class="inline-flex items-center gap-2 rounded-xl border px-3.5 py-2 text-xs font-medium shadow-sm transition-all">
                            <iconify-icon icon="lucide:sliders-horizontal"></iconify-icon>
                            <span>Filters</span>
                            <span x-show="region" x-cloak class="h-2 w-2 rounded-full bg-current"></span>
                        </button>
                    </div>
                </div>

                <div class="w-full">
                    <div class="flex flex-col gap-2.5 rounded-2xl border border-neutral-200 bg-neutral-50/50 p-2 sm:flex-row sm:items-center sm:rounded-full sm:bg-white sm:shadow-sm">
                        <div class="flex min-w-0 flex-1 items-center gap-3 px-3 py-1">
                            <iconify-icon icon="lucide:search" class="shrink-0 text-lg text-neutral-400"></iconify-icon>
                            <input type="search" x-model="search"
                                placeholder="Search destination, country, region or university..."
                                aria-label="Search destinations" autocomplete="off"
                                class="w-full min-w-0 border-0 bg-transparent py-2 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0">
                            <button type="button" x-show="search" x-cloak @click="search = ''"
                                aria-label="Clear search" class="shrink-0 rounded-full p-1.5 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-700">
                                <iconify-icon icon="lucide:x"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <p class="mt-2 px-3 text-xs text-neutral-400">Results update automatically as you type.</p>
                </div>

                <div x-show="showFilters" x-cloak x-transition class="border-t border-neutral-100 pt-5">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400">Filter by Region</span>
                        <button type="button" x-show="region" x-cloak @click="setRegion('')"
                            class="text-xs font-medium text-brand-600 hover:text-brand-800">Reset Region</button>
                    </div>
                    <div class="flex gap-2 overflow-x-auto pb-2">
                        <button type="button" @click="setRegion('')"
                            :class="region === '' ? 'border-neutral-900 bg-neutral-900 text-white' : 'border-neutral-200 bg-white text-neutral-600 hover:border-neutral-300'"
                            class="shrink-0 rounded-xl border px-4 py-2 text-xs font-medium transition-all">All Regions</button>
                        @foreach ($regions as $item)
                            <button type="button" @click="setRegion(@js($item))"
                                :class="region === @js($item) ? 'border-neutral-900 bg-neutral-900 text-white' : 'border-neutral-200 bg-white text-neutral-600 hover:border-neutral-300'"
                                class="shrink-0 rounded-xl border px-4 py-2 text-xs font-medium transition-all">{{ $item }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6 mt-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">
                    <span class="h-2 w-2 rounded-full bg-accent-two-500"></span> EXPLORE THE WORLD
                </div>
                <h2 class="mt-2 text-2xl font-bold tracking-tight text-brand-950 sm:text-3xl">Popular Study Destinations</h2>
                <p class="mt-2 text-sm text-neutral-500">Find the right country for your education journey.</p>
            </div>
            <p class="text-xs font-medium text-neutral-500" aria-live="polite">
                Showing <span x-text="count" class="font-semibold text-brand-950">{{ $destinations->count() }}</span>
                of <span x-text="total" class="font-semibold text-brand-950">{{ $totalDestinations }}</span> destinations
            </p>
        </div>

        <div id="destination-results" x-ref="results" class="relative min-h-[200px] transition-opacity duration-200"
            :class="loading ? 'pointer-events-none opacity-50' : 'opacity-100'"
            :aria-busy="loading.toString()">
            @include('frontend.pages.destinations._results', [
                'destinations' => $destinations,
                'search' => $search,
                'selectedRegion' => $selectedRegion,
            ])
        </div>

        <div x-show="error" x-cloak role="alert"
            class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            x-text="error"></div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/destination-explorer.js') }}"></script>
@endpush