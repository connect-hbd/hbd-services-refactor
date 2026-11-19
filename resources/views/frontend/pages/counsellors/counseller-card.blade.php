@isset($counsellor)
    @php
        $user = $counsellor->user;
        $designation = $counsellor->currentEmployment?->designation;
        $destinations = collect($counsellor->work_destinations ?? [])->filter()->values();
        $expertiseTags = collect($counsellor->expertise ?? [])
            ->filter()
            ->map(fn ($item) => strtoupper(trim((string) $item)))
            ->values();
        $languages = collect($counsellor->languages ?? [])
            ->filter()
            ->map(fn ($item) => ucfirst(trim((string) $item)))
            ->values();
    @endphp

    <a href="{{ route('counsellor.details', $counsellor) }}"
       data-counsellor-card
       class="group flex w-full flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:border-slate-300 hover:shadow-md">

        <div>
            {{-- Header: Avatar, Name & Experience --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3.5">
                    <img class="h-12 w-12 shrink-0 rounded-full border border-slate-200 object-cover"
                         src="{{ $user?->avatar ? asset($user->avatar) : asset('profile.png') }}"
                         alt="{{ $user?->name ?? 'Counsellor' }}" />
                    <div class="min-w-0">
                        <h3 class="truncate text-base font-bold text-slate-900 transition-colors group-hover:text-brand-700">
                            {{ $user?->name ?? 'N/A' }}
                        </h3>
                        <p class="truncate text-xs font-medium text-slate-500">
                            {{ $designation?->name ?? 'Senior Counsellor' }}
                        </p>
                    </div>
                </div>

                <span class="shrink-0 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700">
                    {{ $counsellor->experience_years ?? 0 }}+ yrs
                </span>
            </div>

            {{-- Expertise Badges --}}
            @if ($expertiseTags->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach ($expertiseTags as $tag)
                        <span class="rounded-lg border border-slate-200/70 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold tracking-wide text-slate-700">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif

            {{-- Divider --}}
            <div class="my-4 border-t border-slate-100"></div>

            {{-- Metadata: Target Countries & Languages --}}
            <div class="space-y-2.5 text-xs sm:text-sm text-slate-600">
                @if ($destinations->isNotEmpty())
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <span class="font-semibold text-slate-800">Countries:</span>
                            <span>{{ $destinations->implode(', ') }}</span>
                        </div>
                    </div>
                @endif

                @if ($languages->isNotEmpty())
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
                        </svg>
                        <div>
                            <span class="font-semibold text-slate-800">Languages:</span>
                            <span>{{ $languages->implode(', ') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action CTA --}}
        <div class="mt-6 pt-1">
            <span class="flex w-full items-center justify-center gap-2 rounded-xl bg-brand-50 px-4 py-2.5 text-xs sm:text-sm font-semibold text-brand-700 transition-colors group-hover:bg-slate-900 group-hover:text-white">
                View Profile &amp; Book
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </span>
        </div>
    </a>
@endisset
