@php
    $alpine = $alpine ?? false;
    $university = $university ?? [];
@endphp

<article
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-[0_1px_2px_rgba(16,24,40,0.04)] transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-theme-md">
                        {{-- Card Top Area --}}
                        <div class="relative border-b border-brand-100 bg-brand-25 p-4">
                            <div class="flex h-12 items-center justify-center transition-all duration-300">
                                @if ($alpine)
                                    <template x-if="university.logo">
                                        <img :src="university.logo" :alt="`${university.name || 'University'} logo`"
                                            class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                    </template>
                                    <span x-show="!university.logo" x-cloak class="text-sm font-semibold text-brand-700"
                                        x-text="university.short_name || university.name || 'University'"></span>
                                @elseif (!empty($university['logo']))
                                    <img src="{{ $university['logo'] }}" alt="{{ $university['name'] ?? 'University' }} logo"
                                        class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                @else
                                    <span class="text-sm font-semibold text-brand-700">{{ $university['short_name'] ?? ($university['name'] ?? 'University') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Information --}}
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-center gap-2 text-xs font-medium text-black/50">
                                <iconify-icon icon="lucide:map-pin" class="text-sm text-brand-500"></iconify-icon>
                                @if ($alpine)<span x-text="university.city || university.country || 'Location unavailable'"></span>
                                @else<span>{{ $university['city'] ?? ($university['country'] ?? 'Location unavailable') }}</span>@endif
                            </div>

                            <h3
                                class="mt-2.5 text-lg font-bold text-black transition-colors group-hover:text-brand-600">
                                @if ($alpine)<span x-text="university.name || 'University'"></span>
                                @else{{ $university['name'] ?? 'University' }}@endif
                            </h3>

                            <p class="mt-3 flex-1 text-sm leading-6 text-black/60 line-clamp-3">
                                @if ($alpine)<span x-text="university.description || 'Explore this university and its study opportunities.'"></span>
                                @else{{ $university['description'] ?? 'Explore this university and its study opportunities.' }}@endif
                            </p>

                            {{-- Footer link --}}
                            {{-- <div class="mt-6 border-t border-brand-100 pt-4">
                            <a href="#"
                                class="inline-flex w-full items-center justify-between text-xs font-semibold text-brand-600 transition group-hover:text-brand-700">
                                <span>Get Free Consultation</span>
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-brand-200 bg-brand-50 text-brand-600 transition group-hover:bg-brand-500 group-hover:border-brand-500 group-hover:text-white">
                                    <iconify-icon icon="lucide:arrow-right" class="text-sm"></iconify-icon>
                                </span>
                            </a>
                        </div> --}}
                        </div>
                    </article>
       
