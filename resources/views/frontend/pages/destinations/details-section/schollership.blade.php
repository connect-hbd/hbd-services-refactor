 @php
    $financial = $destination['financialPlanning'] ?? [];

    $scholarshipData = $financial['scholarships'] ?? [];
    $scholarships = $scholarshipData['items'] ?? [];
@endphp
<section id="scholarships" data-section class="relative overflow-hidden bg-white pb-16 sm:pb-20 lg:pb-24">
 {{-- Scholarships Section --}}

 <div id="scholarships" class="scroll-mt-29 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

     {{-- Featured Scholarship --}}

     <div class="relative overflow-hidden rounded-2xl bg-brand-900 p-6 text-white sm:p-10 lg:p-12" data-reveal>

         {{-- Subtle Decorative Glow --}}
         <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-brand-800/40 blur-3xl"
             aria-hidden="true">
         </div>

         <div class="relative grid items-center gap-8 lg:grid-cols-12 lg:gap-12">

             {{-- Left Content: Universal Message & CTAs --}}
             <div class="lg:col-span-7">

                 <span
                     class="inline-flex items-center gap-1.5 rounded-full bg-success-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-success-500 sm:text-[11px]">
                     <iconify-icon icon="lucide:badge-check" class="text-sm"></iconify-icon>
                     Scholarships & Awards
                 </span>

                 <div class="mt-4 flex items-start gap-3.5 sm:mt-5 sm:gap-4">
                     <div
                         class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-brand-300 sm:h-12 sm:w-12">
                         <iconify-icon icon="lucide:trophy" class="text-2xl"></iconify-icon>
                     </div>

                     <div>
                         <h3 class="text-xl font-bold leading-snug text-white sm:text-2xl">
                             GREAT Scholarships for International Students
                         </h3>

                         <p class="mt-1 text-xs text-brand-200 sm:text-sm">
                             Government Grants • University Merit Awards • Tuition Fee Waivers
                         </p>
                     </div>
                 </div>

                 <p class="mt-4 text-sm leading-6 text-brand-100/80 sm:mt-5">
                     Multiple funding opportunities are available depending on your chosen country,
                     university, academic
                     background, and intake cycle. Our advisors assess your profile to match you with
                     verified scholarships
                     and guide you through every step of the application.
                 </p>

                 {{-- Action Buttons --}}
                 <div
                     class="mt-6 flex flex-col flex-wrap items-stretch gap-3 sm:mt-8 sm:flex-row sm:items-center sm:gap-4">
                     <a href="#eligibility"
                         class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:bg-brand-600 sm:px-6">
                         <span>Talk to Scholarship Advisor</span>
                         <iconify-icon icon="lucide:arrow-right" class="text-base"></iconify-icon>
                     </a>

                     <a href="#contact"
                         class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-white/15 sm:px-6">
                         <iconify-icon icon="lucide:headset" class="text-base"></iconify-icon>
                         <span>Check Scholarship Eligibility</span>
                     </a>
                 </div>

             </div>

             {{-- Right Visual: Trust & Coverage Pillars (No Fixed Amount Needed) --}}
             <div class="lg:col-span-5">
                 <div class="rounded-xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm sm:p-6">

                     <div class="flex items-center justify-between border-b border-white/10 pb-3.5">
                         <span class="text-xs font-semibold uppercase tracking-wider text-brand-200">
                             How We Maximize Your Funding
                         </span>
                         <span
                             class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2.5 py-0.5 text-[11px] font-medium text-brand-100">
                             <iconify-icon icon="lucide:shield-check" class="text-xs text-success-400"></iconify-icon>
                             Verified Sources
                         </span>
                     </div>

                     <ul class="mt-4 space-y-3.5">
                         <li class="flex items-start gap-3">
                             <div
                                 class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand-500/20 text-brand-300">
                                 <iconify-icon icon="lucide:landmark" class="text-sm"></iconify-icon>
                             </div>
                             <div>
                                 <p class="text-sm font-semibold text-white">Country &amp; Government
                                     Schemes</p>
                                 <p class="text-xs leading-relaxed text-brand-100/70">
                                     National fellowships &amp; bilateral grants tailored to destination
                                     rules.
                                 </p>
                             </div>
                         </li>

                         <li class="flex items-start gap-3">
                             <div
                                 class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand-500/20 text-brand-300">
                                 <iconify-icon icon="lucide:award" class="text-sm"></iconify-icon>
                             </div>
                             <div>
                                 <p class="text-sm font-semibold text-white">University-Specific Waivers
                                 </p>
                                 <p class="text-xs leading-relaxed text-brand-100/70">
                                     Automatic merit discounts, early-bird bursaries &amp; faculty
                                     awards.
                                 </p>
                             </div>
                         </li>

                         <li class="flex items-start gap-3">
                             <div
                                 class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand-500/20 text-brand-300">
                                 <iconify-icon icon="lucide:file-check-2" class="text-sm"></iconify-icon>
                             </div>
                             <div>
                                 <p class="text-sm font-semibold text-white">Profile-Based Matching</p>
                                 <p class="text-xs leading-relaxed text-brand-100/70">
                                     Tailored shortlisting based on GPA, English score &amp; study level.
                                 </p>
                             </div>
                         </li>
                     </ul>

                 </div>
             </div>

         </div>
     </div>


     {{-- Scholarship Cards --}}
     @if (!empty($scholarships))

         <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">

             @foreach ($scholarships as $scholarship)
                 <div data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms">
                     <article
                         class="group flex h-full flex-col rounded-2xl border border-brand-100 bg-white p-6 shadow-[0_1px_2px_rgba(16,24,40,0.04)] transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-theme-md sm:p-7">

                         <div class="flex items-center gap-3">

                             <div
                                 class="flex h-12 w-12 items-center justify-center rounded-xl border border-brand-100 bg-brand-50 text-brand-600 transition-colors group-hover:bg-brand-500 group-hover:text-white">

                                 <iconify-icon icon="{{ $scholarship['icon'] ?? 'lucide:award' }}" class="text-2xl">
                                 </iconify-icon>

                             </div>

                             @if (!empty($scholarship['category']))
                                 <h4 class="text-lg font-bold text-black transition-colors group-hover:text-brand-600">
                                     {{ $scholarship['category'] }}
                                 </h4>
                             @endif

                         </div>

                         <h4
                             class="mt-5 text-lg font-bold text-neutral-600 transition-colors group-hover:text-brand-600">
                             {{ $scholarship['name'] ?? '' }}
                         </h4>

                         @if (!empty($scholarship['description']))
                             <p class="mt-2 flex-1 text-sm leading-6 text-black/60">
                                 {{ $scholarship['description'] }}
                             </p>
                         @endif

                     </article>
                 </div>
             @endforeach

         </div>

     @endif

 </div>

</section>
