@php
    $alpine = $alpine ?? false;
    $destination = $destination ?? [];
    $image = $destination['image'] ?? '';
    $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image);
@endphp

<div data-reveal class="h-full" @unless ($alpine) style="--reveal-delay: {{ (($loop->index ?? 0) % 3) * 80 }}ms" @endunless>
    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-[0_1px_2px_rgba(16,24,40,0.04)] transition-all duration-300 hover:-translate-y-1.5 hover:border-brand-200 hover:shadow-[0_24px_48px_-24px_rgba(16,24,40,0.28)]">
        <div class="relative aspect-[16/10] shrink-0 overflow-hidden">
            @if ($alpine)
                <img :src="imageUrl(destination.image)"
                    :alt="`${destination.title || destination.name || destination.country || 'Destination'}`"
                    loading="lazy"
                    class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
            @else
                <img src="{{ $imageUrl }}" alt="{{ $destination['title'] ?? ($destination['country'] ?? 'Destination') }}"
                    loading="lazy"
                    class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

            <div class="absolute left-4 top-4 z-10 flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-white shadow-sm">
                    @if ($alpine)
                        <img :src="`https://flagcdn.com/w40/${String(destination.flagCode || 'un').toLowerCase()}.png`"
                            :alt="`${destination.name || 'Destination'} flag`" class="h-6 w-6 rounded-full object-cover" loading="lazy">
                    @else
                        <img src="https://flagcdn.com/w40/{{ strtolower($destination['flagCode'] ?? ($destination['flag'] ?? 'un')) }}.png"
                            alt="{{ $destination['country'] ?? 'Country' }} flag" class="h-6 w-6 rounded-full object-cover" loading="lazy">
                    @endif
                </span>

                @if ($alpine)
                    <span x-show="destination.is_featured" class="inline-flex items-center gap-1 rounded-full bg-brand-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow-sm"><i class="fa-solid fa-star"></i> Featured</span>
                    <span x-show="!destination.is_featured && destination.popular" class="inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-brand-700 shadow-sm">Popular</span>
                @else
                    @if (!empty($destination['is_featured']) || !empty($destination['popular']))
                        <span @class([
                            'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide shadow-sm',
                            'bg-brand-500 text-white' => !empty($destination['is_featured']),
                            'bg-white/95 text-brand-700' => empty($destination['is_featured']) && !empty($destination['popular']),
                        ])>
                            @if (!empty($destination['is_featured'])) <i class="fa-solid fa-star"></i> Featured @else Popular @endif
                        </span>
                    @endif
                @endif
            </div>

            @if ($alpine)
                <div x-show="destination.rating" class="absolute right-4 top-4 z-10 inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-xs font-bold text-black shadow-sm">
                    <i class="fa-solid fa-star text-brand-500"></i><span x-text="Number(destination.rating).toFixed(1)"></span>
                </div>
            @elseif (isset($destination['rating']) && is_numeric($destination['rating']))
                <div class="absolute right-4 top-4 z-10 inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-xs font-bold text-black shadow-sm">
                    <i class="fa-solid fa-star text-brand-500"></i>{{ number_format((float) $destination['rating'], 1) }}
                </div>
            @endif

            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent p-6 backdrop-blur-[2px]">
                <div class="flex flex-col gap-1.5">
                    <div class="inline-flex w-fit items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-0.5 text-xs font-medium text-white/90 backdrop-blur-md ring-1 ring-inset ring-white/20">
                        <i class="fa-solid fa-location-dot text-[11px] text-amber-400"></i>
                        @if ($alpine)
                            <span x-text="destination.name || 'Global'"></span>
                        @else
                            <span>{{ $destination['name'] ?? 'Global' }}</span>
                        @endif
                    </div>
                    <h3 class="text-xl text-start font-bold text-white drop-shadow-sm sm:text-2xl">
                        Study in
                        @if ($alpine)
                            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent" x-text="destination.name || destination.country || 'Destination'"></span>
                        @else
                            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">{{ $destination['name'] ?? 'Destination' }}</span>
                        @endif
                    </h3>
                </div>
            </div>
        </div>

        <div class="flex flex-1 flex-col p-6">
            @if ($alpine)
                <p class="line-clamp-2 text-start text-sm leading-6 text-black/60" x-text="destination.description || 'Explore study opportunities in this destination.'"></p>
            @else
                <p class="line-clamp-2 text-start text-sm leading-6 text-black/60">{{ $destination['description'] ?? 'Explore study opportunities in this destination.' }}</p>
            @endif

            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between gap-3 rounded-xl border border-neutral-100 bg-neutral-50 px-3 py-2 text-xs">
                    <span class="font-medium text-neutral-500">Avg. Cost</span>
                    @if ($alpine)
                        <span class="text-right font-semibold text-neutral-800" x-text="destination.avgCost || 'Contact for details'"></span>
                    @else
                        <span class="text-right font-semibold text-neutral-800">{{ $destination['avgCost'] ?? 'Contact for details' }}</span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-3 rounded-xl border border-neutral-100 bg-neutral-50 px-3 py-2 text-xs">
                    <span class="font-medium text-neutral-500">Visa Processing</span>
                    @if ($alpine)
                        <span class="text-right font-semibold text-neutral-800" x-text="destination.visaProcessing || 'Varies by country'"></span>
                    @else
                        <span class="text-right font-semibold text-neutral-800">{{ $destination['visaProcessing'] ?? 'Varies by country' }}</span>
                    @endif
                </div>
            </div>

            <div class="mt-auto flex items-center justify-end border-t border-neutral-100 pt-4">
                <span class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600">
                    Learn more <i class="fa-solid fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                </span>
            </div>
        </div>
    </article>
</div>
