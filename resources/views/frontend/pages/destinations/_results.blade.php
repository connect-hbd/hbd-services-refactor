<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($destinations as $destination)
        @php
            // Change this route name if your destination details route uses another name.
            $destinationUrl = !empty($destination['slug'])
                ? url('/destinations/' . $destination['slug'])
                : '#';
        @endphp

        <a href="{{ $destinationUrl }}" class="group block h-full">
            @include('frontend.pages.destinations.card', ['destination' => $destination])
        </a>
    @empty
        <div class="col-span-full rounded-3xl border border-dashed border-neutral-300 bg-white px-6 py-20 text-center" data-reveal>
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-500">
                <i class="fa-solid fa-compass text-3xl"></i>
            </div>
            <h3 class="mt-5 text-xl font-bold text-brand-950">
                {{ $search !== '' || $selectedRegion !== '' ? 'No Matching Destinations' : 'No Destinations Available' }}
            </h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-neutral-500">
                {{ $search !== '' || $selectedRegion !== ''
                    ? 'Try another search term or choose a different region.'
                    : 'Destinations will be available soon.' }}
            </p>
            <button type="button" @click="$dispatch('destination-reset')"
                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-700">
                <i class="fa-solid fa-rotate-left"></i> Reset Filters
            </button>
        </div>
    @endforelse
</div>
