@props([
    'destination' => [],
])

@php
    $destName = $destination['code'];
    $universities = $destination['universities'] ?? [];
@endphp


<section id="universities" data-section class="relative overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="mb-12 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <x-destination-section-heading eyebrow="PARTNER INSTITUTIONS" :title="'Leading Universities in <span class=\'text-brand-500\'>' . $destName . '</span>'"
                description="Explore premier institutions renowned for academic standards, cutting-edge facilities, and excellent international student support." />

            {{-- <a href="{{ route('universities') }}"
               class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl border border-brand-200 bg-white px-5 py-3 text-sm font-semibold text-brand-700 shadow-sm transition-all duration-300 hover:border-brand-300 hover:bg-brand-50 lg:self-auto">
                <span>View All Universities</span>
                <iconify-icon icon="lucide:arrow-up-right" class="text-base"></iconify-icon>
            </a> --}}
        </div>

        {{-- Universities Grid --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($universities as $university)
             <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 80 }}ms">
                @include('frontend.pages.universities.card', [
                    'university' => $university,
                ])
             </div>
            @endforeach
        </div>
    </div>
</section>
