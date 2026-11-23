@extends('frontend.layouts.app')

@section('content')
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
        <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="grid grid-cols-1 lg:grid-cols-12">

                <!-- Left Info Panel -->
                <div class="lg:col-span-6 relative flex flex-col justify-between p-8 sm:p-10 lg:p-12 text-white bg-cover bg-center overflow-hidden"
                    style="background-image: url('{{ asset('frontend-img/api-form.jpg') }}');">

                    <!-- Layered Gradient & Ambient Glow Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-[#155b9d]/95 via-[#1968b2]/90 to-slate-950/95"></div>
                    <div
                        class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-sky-400/15 blur-3xl pointer-events-none">
                    </div>
                    <div
                        class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-[#1968b2]/30 blur-3xl pointer-events-none">
                    </div>

                    <!-- Top Content -->
                    <div class="relative z-10">
                        <!-- Status Pill -->
                        <div
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold tracking-wider uppercase text-blue-50 mb-7 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Free Counselling Session
                        </div>

                        <!-- Headline -->
                        <h2
                            class="text-2xl sm:text-3xl lg:text-[2rem] font-bold tracking-tight leading-[1.2] text-white mb-4">
                            Ready to Make Your Study Abroad Dreams a Reality?
                        </h2>

                        <!-- Subtitle -->
                        <p class="text-blue-100/90 text-sm sm:text-base leading-relaxed max-w-md">
                            Share your details and let our expert counsellors guide you through your study options,
                            admissions, and next steps toward your future.
                        </p>

                        <!-- Key Counselling Highlights -->
                        <ul class="mt-8 space-y-3.5 text-sm text-blue-50/95">
                            <li class="flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-full bg-white/15 border border-white/20 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span>Personalized University &amp; Country Matching</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-full bg-white/15 border border-white/20 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span>Scholarship Assessment &amp; Application Support</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-full bg-white/15 border border-white/20 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span>End-to-End Student Visa Guidance</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Bottom Glassmorphic Trust Bar -->
                    <div class="relative z-10 mt-10 pt-6 border-t border-white/15">
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 px-4 py-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 text-xs sm:text-sm font-medium text-white">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>100% Free Entry</span>
                            </div>

                            <span class="hidden sm:block h-4 w-px bg-white/20"></span>

                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-sky-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Secure Registration</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Form Panel -->
                <div class="lg:col-span-6 p-5 sm:p-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Book Appointment</h3>
                    @include('frontend.pages.api-forms._form')
                </div>
            </div>
        </div>
    </section>
@endsection
