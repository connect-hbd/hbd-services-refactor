<!-- Avatar and Rating Header -->
<div class="text-center">
    <div class="relative inline-block mb-4">
        <img src="{{ asset($counsellor['user']['avatar'] ?? 'frontend-img/prisha.jpg') }}"
            alt="{{ $counsellor['user']['name'] ?? 'Counsellor' }}"
            class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover mx-auto ring-4 ring-brand-100">
        <span class="absolute bottom-1 right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white"
            title="Available Online"></span>
    </div>
    <h2 class="text-xl font-bold text-neutral-900">
        {{ $counsellor['user']['name'] ?? trim(($counsellor['first_name'] ?? '') . ' ' . ($counsellor['last_name'] ?? '')) }}
    </h2>
    <p class="text-xs font-medium text-brand-600 uppercase tracking-wider mt-0.5">
        {{ $counsellor['current_employment']['designation']['name'] ?? 'Counsellor' }}</p>
</div>

<hr class="border-neutral-100">

<!-- Quick Stats Grid -->
<div class="grid grid-cols-2 gap-3 text-center">
    <div class="bg-brand-50/50 border border-brand-100 p-3 rounded-2xl">
        <span class="block text-xs text-neutral-500">Experience</span>
        <span class="text-base font-bold text-brand-700">{{ $counsellor['experience_years'] ?? 0 }}+ Years</span>
    </div>
    <div class="bg-brand-50/50 border border-brand-100 p-3 rounded-2xl">
        <span class="block text-xs text-neutral-500">Education</span>
        <span class="text-sm font-bold text-brand-700">{{ $counsellor['education'] ?: 'Not provided' }}</span>
    </div>
</div>

<!-- Short Bio -->
<div class="space-y-2">
    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400">About Counsellor</h3>
    <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed">
        {{ $counsellor['bio'] ?? 'N/A' }}
    </p>
</div>

<!-- Expertise Tags -->
<div class="space-y-2">
    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400">Specializations</h3>
    @if (!empty($counsellor['expertise']))
        <div class="flex flex-wrap gap-1.5">
            @foreach ($counsellor['expertise'] as $expertise)
                <span class="text-xs bg-neutral-100 text-neutral-700 font-medium px-3 py-1 rounded-full">
                    {{ strtoupper($expertise) }} </span>
            @endforeach
        </div>
    @endif
</div>

 {{-- Languages --}}
            @if (!empty($counsellor['languages']))
                <div class="mt-4 flex items-center gap-1.5 text-sm text-slate-500"> <svg
                        class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path
                            d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
                    </svg> <span> <strong class="font-semibold text-slate-700"> Languages : </strong>
                        {{ collect($counsellor['languages'])->filter()->implode(', ') }} </span> </div>
            @endif
            @if (!empty($counsellor['work_destinations']))
                <div class="mt-4 text-sm text-slate-500">
                    <strong class="font-semibold text-slate-700">Destinations:</strong>
                    {{ collect($counsellor['work_destinations'])->filter()->implode(', ') }}
                </div>
            @endif

<!-- Trust Badges -->
<div class="pt-2 border-t border-neutral-100 space-y-2 text-xs text-neutral-500">
    <div class="flex items-center space-x-2">
        <i class="fa-solid fa-shield-halved text-brand-500"></i>
        <span>100% Confidential & Private</span>
    </div>
    <div class="flex items-center space-x-2">
        <i class="fa-solid fa-video text-brand-500"></i>
        <span>Includes HD Video Call & Notes</span>
    </div>
</div>
