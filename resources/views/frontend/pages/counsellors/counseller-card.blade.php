@isset($counsellor)
    @php
        $user = data_get($counsellor, 'user');
        $fullName = trim((string) (
            data_get($user, 'name')
            ?? trim((data_get($counsellor, 'first_name') ?? '') . ' ' . (data_get($counsellor, 'last_name') ?? ''))
        ));
        $userName = filled($fullName) ? $fullName : 'Counsellor';

        $experienceYears = max(0, (int) (data_get($counsellor, 'experience_years') ?? 0));

        $designationName = trim((string) (
            data_get($counsellor, 'currentEmployment.designation.name')
            ?? data_get($counsellor, 'current_employment.designation.name')
            ?? 'Counsellor'
        ));
      

        $education = trim((string) (data_get($counsellor, 'education') ?? ''));
        $institution = trim((string) (data_get($counsellor, 'institution') ?? ''));
        $bio = trim((string) (data_get($counsellor, 'bio') ?? ''));

        $avatarPath = data_get($user, 'avatar');
        $avatarUrl = filled($avatarPath) ? asset($avatarPath) : asset('profile.png');

        $destinations = collect(data_get($counsellor, 'work_destinations', []))
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->values();

        $expertiseTags = collect(data_get($counsellor, 'expertise', []))
            ->map(fn ($item) => strtoupper(trim((string) $item)))
            ->filter()
            ->unique()
            ->values();

        $maxVisibleTags = 4;
        $visibleExpertise = $expertiseTags->take($maxVisibleTags);
        $extraExpertiseCount = max(0, $expertiseTags->count() - $visibleExpertise->count());

        $languages = collect(data_get($counsellor, 'languages', []))
            ->map(fn ($item) => ucfirst(strtolower(trim((string) $item))))
            ->filter()
            ->unique()
            ->values();

        $profileUrl = route('counsellor.details', $counsellor);
        $bookingUrl = route('counsellor.details', $counsellor) . '#book';
    @endphp

    <article data-counsellor-card
             aria-label="Counsellor profile: {{ $userName }}"
             class="group flex h-full w-full flex-col justify-between rounded-2xl border border-brand-100 bg-white p-6 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-lg hover:shadow-secondary-500/8">

        <div>
            {{-- 1. Top Row: Avatar, Name, Designation & Experience --}}
            <div class="flex items-start gap-3.5">
                <a href="{{ $profileUrl }}" class="relative shrink-0 focus:outline-none">
                    <img class="h-16 w-16 rounded-full border border-brand-100 object-cover ring-2 ring-brand-25 transition-transform duration-200 group-hover:scale-[1.03]"
                         src="{{ $avatarUrl }}"
                         alt="{{ $userName }}"
                         loading="lazy" />                
                </a>

                <div class="min-w-0 flex-1">
                    <a href="{{ $profileUrl }}" class="block focus:outline-none">
                        <h3 class="truncate text-base sm:text-lg font-bold tracking-tight text-secondary-500 transition-colors duration-200 group-hover:text-brand-600">
                            {{ $userName }}
                        </h3>
                    </a>

                    {{-- Designation + Experience Pill Row --}}
                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                        @if (filled($designationName))
                            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-[11px] font-semibold bg-brand-500 text-white tracking-wide ring-1 ring-inset">
                                {{ $designationName }}
                            </span>
                        @endif

                        @if ($experienceYears > 0)
                            <span title="{{ $experienceYears }} years of experience"
                                  class="inline-flex items-center gap-1 rounded-md bg-success-50 px-2 py-0.5 text-[11px] font-bold text-success-800 ring-1 ring-inset ring-success-200">
                                <svg class="h-3 w-3 text-success-600" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $experienceYears }}+ yrs
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Academic Background Highlight Box (Education & Where Studied) --}}
            @if (filled($education) || filled($institution))
                <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-brand-100/90 bg-brand-25/80 px-3.5 py-2.5">
                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-white text-brand-600 shadow-2xs ring-1 ring-brand-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.9">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1 text-xs">
                        @if (filled($education))
                            <p class="font-bold text-secondary-500">
                                {{ $education }}
                            </p>
                        @endif
                        @if (filled($institution))
                            <p class="mt-0.5 truncate font-medium text-slate-600" title="{{ $institution }}">
                                Studied at <span class="font-semibold text-brand-700">{{ $institution }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 3. Key Counselling Details: Target Countries & Spoken Languages --}}
            <dl class="mt-4 divide-y divide-slate-100 border-y border-slate-100 text-xs sm:text-[13px]">
                @if ($destinations->isNotEmpty())
                    <div class="flex items-start justify-between gap-4 py-2.5">
                        <dt class="flex shrink-0 items-center gap-2 font-medium text-slate-500">
                            <svg class="h-4 w-4 text-accent-two-600" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span>Target Countries</span>
                        </dt>
                        <dd class="text-right font-semibold text-secondary-500">
                            {{ $destinations->implode(' • ') }}
                        </dd>
                    </div>
                @endif

                @if ($languages->isNotEmpty())
                    <div class="flex items-start justify-between gap-4 py-2.5">
                        <dt class="flex shrink-0 items-center gap-2 font-medium text-slate-500">
                            <svg class="h-4 w-4 text-accent-one-600" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                            </svg>
                            <span>Spoken Languages</span>
                        </dt>
                        <dd class="text-right font-semibold text-secondary-500">
                            {{ $languages->implode(', ') }}
                        </dd>
                    </div>
                @endif
            </dl>
           

            {{-- 5. Specializations Pills --}}
            @if ($visibleExpertise->isNotEmpty())
                <div class="mt-4">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Specializations
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($visibleExpertise as $tag)
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-brand-200 bg-brand-25/80 px-3 py-1 text-xs font-semibold text-brand-700 transition-colors group-hover:border-brand-300 group-hover:bg-brand-50">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                {{ $tag }}
                            </span>
                        @endforeach

                        @if ($extraExpertiseCount > 0)
                            <span class="inline-flex items-center rounded-lg border border-dashed border-brand-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-500">
                                +{{ $extraExpertiseCount }} more
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- 6. Dual Action Buttons --}}
        <div class="mt-6 grid grid-cols-2 items-center gap-3 pt-1">
            <a href="{{ $profileUrl }}"
               class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-brand-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm font-semibold text-secondary-500 transition-all duration-150 hover:border-brand-300 hover:bg-brand-25 hover:text-brand-700 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
               <iconify-icon icon="lucide:user" class="text-base"></iconify-icon> 
               <span>View Profile</span>
            </a>

            <a href="{{ $bookingUrl }}"
               class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-brand-500 px-3.5 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs shadow-brand-500/20 transition-all duration-150 hover:bg-secondary-500 hover:shadow-md hover:shadow-secondary-500/20 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-1">
                <iconify-icon icon="lucide:calendar" class="text-base"></iconify-icon>
                <span>Book Now</span>
            </a>
        </div>
    </article>
@endisset