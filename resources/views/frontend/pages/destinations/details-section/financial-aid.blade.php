@php
    $financial = $destination['financialPlanning'] ?? [];

    $tuitionFees = $financial['tuitionFees'] ?? [];
    $livingCosts = $financial['livingCosts'] ?? [];
    $livingAreas = $livingCosts['areas'] ?? [];

    $scholarshipData = $financial['scholarships'] ?? [];
    $featuredScholarship = $scholarshipData['featured'] ?? null;
    $scholarships = $scholarshipData['items'] ?? [];

    $destName = $destination['name'] ?? 'Destination';
@endphp

@if (!empty($financial))

    <section id="costs" data-section class="relative overflow-hidden bg-white py-16 sm:py-20 lg:py-24">

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <x-destination-section-heading eyebrow="FINANCIAL PLANNING & EXPENSES" :title="'Estimated Cost of Studying in <span class=\'text-brand-500\'>' . e($destName) . '</span>'"
                description="Understand key tuition fee ranges, realistic monthly living expenses, and budgeting factors before making your study decision." />

            {{-- Tuition Fee Cards --}}
            @if (!empty($tuitionFees))
                <div class="mt-12 grid gap-6 lg:grid-cols-2">

                    @foreach ($tuitionFees as $fee)
                    <div  data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms">
                        <article
                            class="group rounded-2xl border border-brand-100 bg-white p-7 shadow-[0_1px_2px_rgba(16,24,40,0.04)] transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-theme-md sm:p-8"
                           >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl border border-brand-100 bg-brand-50 text-brand-600 transition-colors group-hover:bg-brand-500 group-hover:text-white">

                                    <iconify-icon icon="{{ $fee['icon'] ?? 'lucide:graduation-cap' }}" class="text-2xl">
                                    </iconify-icon>

                                </div>

                               <h3 class="text-xl font-bold text-black transition-colors group-hover:text-brand-600">
                                {{ $fee['title'] ?? ($fee['level'] ?? '') }}
                            </h3>

                            </div>

                            <div class="mt-4">

                                @if (!empty($fee['studentCategory']))
                                    <span class="text-xs font-semibold uppercase tracking-wider text-black/50">
                                        {{ $fee['studentCategory'] }}
                                    </span>
                                @endif

                                <div class="mt-1 text-3xl font-extrabold tracking-tight text-brand-600 sm:text-4xl">

                                    {{ $fee['currency'] ?? '' }} {{ number_format($fee['minimum'] ?? 0) }}

                                    –

                                    {{ $fee['currency'] ?? '' }} {{ number_format($fee['maximum'] ?? 0) }} +

                                </div>

                                @if (!empty($fee['period']))
                                    <span class="text-xs text-black/50">
                                        {{ $fee['period'] }}
                                    </span>
                                @endif

                            </div>

                            @if (!empty($fee['description']))
                                <p class="mt-4 text-sm leading-6 text-black/60 line-clamp-2">
                                    {{ $fee['description'] }}
                                </p>
                            @endif

                            

                        </article>
                        </div>
                    @endforeach

                </div>
            @endif


            {{-- Monthly Living Expenses --}}
            @if (!empty($livingAreas))
                <div class="mt-16">

                    <div class="mb-8">

                        <h3 class="text-xl font-bold text-black sm:text-2xl">
                            {{ $livingCosts['title'] ?? 'Estimated Living Costs' }}
                        </h3>

                        @if (!empty($livingCosts['description']))
                            <p class="mt-1.5 text-sm text-black/60">
                                {{ $livingCosts['description'] ?? 'Indicative monthly student budget. Costs depend on city, accommodation type and personal lifestyle; these figures are not immigration financial thresholds.' }}
                            </p>
                        @endif

                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        @foreach ($livingAreas as $area)
                            <div class="rounded-2xl border border-brand-100 bg-brand-25 p-4 shadow-sm sm:p-5 md:p-7"
                                data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms">

                                <div class="flex items-center justify-between gap-4">

                                    <div>

                                        @if (!empty($area['category']))
                                            <span
                                                class="text-[10px] font-semibold uppercase tracking-wider text-brand-600">
                                                {{ $area['category'] }}
                                            </span>
                                        @endif

                                        <h4 class="mt-0.5 text-lg font-bold text-black sm:text-xl">
                                            {{ $area['name'] ?? '' }}
                                        </h4>

                                    </div>

                                    <div class="shrink-0 text-right">

                                        <span class="text-lg font-extrabold text-black sm:text-xl">
                                            {{ $area['currency'] ?? '' }} {{ $area['amount'] ?? '0' }}
                                        </span>

                                        <span class="block text-xs text-black/50">
                                            {{ $livingCosts['period'] ?? 'Per month' }}
                                        </span>

                                    </div>

                                </div>

                                @if (!empty($area['description']))
                                    <p class="mt-3 text-sm leading-6 text-black/60">
                                        {{ $area['description'] }}
                                    </p>
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>
            @endif


          

        </div>
    </section>

@endif
