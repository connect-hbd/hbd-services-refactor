<!-- FOOTER -->
<footer class="bg-neutral-800 text-neutral-400 pt-10 sm:pt-12 md:pt-16 pb-8 border-t border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Main Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 sm:gap-10 mb-10 sm:mb-12">

            <!-- Brand + Social -->
            <div class="lg:col-span-5 -mt-10 flex flex-col items-center sm:items-start text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start sm:items-start space-x-2 mb-4">
                    <div class="w-36 sm:w-40 p-1.5">
                        <a href="/">
                            <img src="{{ asset('logo.png') }}" alt="logo" class="w-auto h-auto">
                        </a>
                    </div>
                </div>
                <p class="text-sm sm:text-[15px] text-neutral-400 w-[90%] leading-relaxed mb-6">
                    HBD Services is a leading study abroad consultancy operating in Bangladesh, partnering with top-ranked institutions worldwide. we provide education, training, and student counselling services across multiple destinations.
                </p>
                <h4
                    class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-white flex items-center justify-center sm:items-start sm:justify-start">
                    Follow Us
                </h4>
                <div class="flex space-x-3 items-center justify-center sm:justify-start">
                    <a href="https://www.facebook.com/hbdservices"
                        class="w-8 h-8 sm:w-9 sm:h-9 bg-brand-500 rounded-full flex items-center justify-center text-white hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-facebook-f text-sm sm:text-base"></i>
                    </a>
                    <a href="https://twitter.com/hbdservicesbd"
                        class="w-8 h-8 sm:w-9 sm:h-9 bg-brand-500 rounded-full flex items-center justify-center text-white hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-twitter text-sm sm:text-base"></i>
                    </a>
                    <a href="https://www.instagram.com/hbd_services_bd"
                        class="w-8 h-8 sm:w-9 sm:h-9 bg-brand-500 rounded-full flex items-center justify-center text-white hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-instagram text-sm sm:text-base"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/hbdservices/"
                        class="w-8 h-8 sm:w-9 sm:h-9 bg-brand-500 rounded-full flex items-center justify-center text-white hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-linkedin-in text-sm sm:text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="lg:col-span-2 flex flex-col items-center justify-center sm:items-start sm:justify-start">
                <h4 class="text-white text-sm sm:text-base font-bold uppercase tracking-wider mb-3 sm:mb-4">Quick Links
                </h4>
                <ul class="space-y-2.5 text-sm sm:text-[15px] text-center sm:text-left">
                    <li>
                        <a href="{{ route('about') }}"
                            class="inline-flex items-center gap-1 hover:text-brand-400 transition">
                            About Us
                            <iconify-icon icon="lucide:arrow-up-right" class="text-sm text-neutral-500"></iconify-icon>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center gap-1 hover:text-brand-400 transition">
                            Contact Us
                            <iconify-icon icon="lucide:arrow-up-right" class="text-sm text-neutral-500"></iconify-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="inline-flex items-center gap-1 hover:text-brand-400 transition">
                            Meet Our Experts
                            <iconify-icon icon="lucide:arrow-up-right" class="text-sm text-neutral-500"></iconify-icon>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events') }}"
                            class="inline-flex items-center gap-1 hover:text-brand-400 transition">
                            Explore Events
                            <iconify-icon icon="lucide:arrow-up-right" class="text-sm text-neutral-500"></iconify-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="inline-flex items-center gap-1 hover:text-brand-400 transition">
                            Our Expo
                            <iconify-icon icon="lucide:arrow-up-right" class="text-sm text-neutral-500"></iconify-icon>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Enquiries Section -->
            <div class="lg:col-span-5">

                <div class="w-full ">

                    {{-- Top Row: Admissions (Book Meeting) & IELTS/PTE (Register) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Admissions Action Box -->
                        <div
                            class="group flex flex-col justify-between rounded-xl border border-white/10 bg-white/3 p-4 transition-all duration-200 hover:border-accent-one-400/40 hover:bg-white/[0.06]">
                            <div class="flex items-center gap-3 mb-3.5">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-accent-one-400 transition-colors group-hover:bg-accent-one-500/15">
                                    <iconify-icon icon="lucide:graduation-cap" class="text-xl"></iconify-icon>
                                </div>
                                <div>
                                    <span
                                        class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400">Study
                                        Abroad</span>
                                    <h5 class="text-sm font-bold text-white">Admissions</h5>
                                </div>
                            </div>

                            <a href="#" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-brand-600 px-3 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-500">
                                <iconify-icon icon="lucide:calendar" class="text-sm shrink-0"></iconify-icon>
                                <span>Book Meeting</span>
                            </a>
                        </div>

                        <!-- IELTS / PTE Action Box -->
                        <div
                            class="group flex flex-col justify-between rounded-xl border border-white/10 bg-white/3 p-4 transition-all duration-200 hover:border-accent-one-400/40 hover:bg-white/[0.06]">
                            <div class="flex items-center gap-3 mb-3.5">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-accent-one-400 transition-colors group-hover:bg-accent-one-500/15">
                                    <iconify-icon icon="lucide:book-open-check" class="text-xl"></iconify-icon>
                                </div>
                                <div>
                                    <span
                                        class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400">Language
                                        Prep</span>
                                    <h5 class="text-sm font-bold text-white">IELTS / PTE</h5>
                                </div>
                            </div>

                            <a href="https://hbdlanguageacademy.com/" target="_blank" rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-white/15 bg-white/10 px-3 py-2.5 text-xs font-semibold text-white transition-all hover:border-accent-one-400/50 hover:bg-accent-one-500/20 hover:text-accent-one-300">
                                <iconify-icon icon="lucide:external-link" class="text-sm shrink-0"></iconify-icon>
                                <span>Register Now</span>
                            </a>
                        </div>

                    </div>

                    {{-- Bottom Row: Main Email, Phone Contact & WhatsApp --}}
                    <div class="mt-5 flex flex-col gap-3 rounded-xl border border-white/10 bg-white/2 p-4 ">

                        {{-- Main Email --}}
                        <a href="mailto:info@hbdservices.com"
                            class="flex items-center justify-between ">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <iconify-icon icon="lucide:mail"
                                    class="text-base shrink-0 text-accent-one-400"></iconify-icon>
                                <span
                                    class="truncate text-xs sm:text-sm font-semibold text-neutral-200 group-hover:text-white">
                                    info@hbdservices.com
                                </span>
                            </div>
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-neutral-500 group-hover:text-accent-one-400">
                                Email Us
                            </span>
                        </a>

                        {{-- Contact Number & WhatsApp Buttons --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Direct Call --}}
                            <a href="tel:09617990099"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-3.5 py-2.5 text-xs font-semibold text-white transition-all hover:border-white/25 hover:bg-white/10">
                                <iconify-icon icon="lucide:phone"
                                    class="text-sm shrink-0 text-accent-one-400"></iconify-icon>
                                <span>09617-990099</span>
                            </a>

                            {{-- WhatsApp --}}
                            <a href="https://wa.me/61485055895" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#25D366]/30 bg-[#25D366]/10 px-3.5 py-2.5 text-xs font-semibold text-[#25D366] transition-all hover:bg-[#25D366] hover:text-white">
                                <svg class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                <span>WhatsApp</span>
                            </a>

                        </div>

                    </div>

                </div>
            </div>

        </div>


        <!-- GLOBAL OFFICES SECTION -->


        <div class="pt-8 sm:pt-10 border-t border-neutral-600 mb-8 sm:mb-10">

            {{-- Section Header + View All Offices Link --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-accent-one-400/20 bg-accent-one-500/10 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-accent-one-400">
                    <iconify-icon icon="lucide:globe" class="text-sm"></iconify-icon>
                    Our Global Presence
                </span>

                {{-- Replace href="#" with your actual offices route, e.g., {{ route('contact') }} or {{ route('offices') }} --}}
                <a href="{{ route('contact') }}"
                    class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-neutral-300 transition-colors hover:text-accent-one-400">
                    <span>Explore Our Global Locations</span>
                    <iconify-icon icon="lucide:arrow-up-right"
                        class="text-base text-accent-one-400 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></iconify-icon>
                </a>
            </div>

            {{-- Two Offices Grid --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:gap-6">

                {{-- CARD 1: Australia Office --}}
                <div
                    class="group flex flex-col justify-between rounded-2xl border border-white/10 bg-white/[0.04] p-5 backdrop-blur-sm transition-all duration-300 hover:border-accent-one-400/30 hover:bg-white/[0.06]">
                    <div>
                        {{-- Header --}}
                        <div class="flex items-center justify-between gap-2 w-full">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-6 w-8 shrink-0 items-center justify-center overflow-hidden rounded  bg-white/5">
                                    <img src="https://flagcdn.com/w40/au.png" alt="Australia flag" width="40"
                                        height="30" class="h-full w-full object-cover" loading="lazy">
                                </div>
                                <h4 class="text-[13px] font-bold uppercase tracking-wider text-white">
                                    Australia Office
                                </h4>
                            </div>

                            <span
                                class="rounded-full border border-accent-one-400/20 bg-accent-one-500/10 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-accent-one-400">
                                Head Office
                            </span>
                        </div>

                        {{-- Address --}}
                        <div class="mt-3.5 flex items-start gap-2 text-sm leading-relaxed text-neutral-300">
                            <iconify-icon icon="lucide:map-pin"
                                class="mt-1 shrink-0 text-base text-accent-one-400"></iconify-icon>
                            <span>Unit 127 (Level 8), Museum Tower, 267-277 Castlereagh St, Sydney NSW,
                                Australia-2000</span>
                        </div>
                    </div>

                    {{-- Contact Links --}}
                    <div
                        class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-white/10 pt-3.5 text-xs font-bold">
                        <a href="tel:+61297408672"
                            class="inline-flex items-center gap-1.5 text-white transition-colors hover:text-accent-one-300">
                            <iconify-icon icon="lucide:phone" class="text-sm text-accent-one-400"></iconify-icon>
                            <span>+ 61 2 9740 8672</span>
                        </a>

                        <a href="https://wa.me/61402299098" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 text-white transition-colors hover:text-emerald-400">
                            <svg class="h-3.5 w-3.5 shrink-0 fill-current text-emerald-400" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            <span>+ 61 402 299 098</span>
                        </a>

                        <a href="mailto:info@hbdservices.com"
                            class="inline-flex items-center gap-1.5 text-white transition-colors hover:text-accent-one-300">
                            <iconify-icon icon="lucide:mail" class="text-sm text-accent-one-400"></iconify-icon>
                            <span>info@hbdservices.com</span>
                        </a>
                    </div>
                </div>

                {{-- CARD 2: Bangladesh Office --}}
                <div
                    class="group flex flex-col justify-between rounded-2xl border border-white/10 bg-white/[0.04] p-5 backdrop-blur-sm transition-all duration-300 hover:border-accent-one-400/30 hover:bg-white/[0.06]">
                    <div>
                        {{-- Header --}}
                        <div class="flex items-center justify-between gap-2 w-full">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-6 w-8 shrink-0 items-center justify-center overflow-hidden rounded  bg-white/5">
                                    <img src="https://flagcdn.com/w40/bd.png" alt="Bangladesh flag" width="40"
                                        height="30" class="h-full w-full object-cover" loading="lazy">
                                </div>
                                <h4 class="text-[13px] font-bold uppercase tracking-wider text-white">
                                    Bangladesh Office
                                </h4>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mt-3.5 flex items-start gap-2 text-sm leading-relaxed text-neutral-300">
                            <iconify-icon icon="lucide:map-pin"
                                class="mt-1 shrink-0 text-base text-accent-one-400"></iconify-icon>
                            <span>Standard Center, 27/1, 7th Floor, New Eskaton Road, Dhaka-1000</span>
                        </div>
                    </div>

                    {{-- Contact Links --}}
                    <div
                        class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-white/10 pt-3.5 text-xs font-bold">
                        <a href="tel:09617990099"
                            class="inline-flex items-center gap-1.5 text-white transition-colors hover:text-accent-one-300">
                            <iconify-icon icon="lucide:phone" class="text-sm text-accent-one-400"></iconify-icon>
                            <span>09617-990099</span>
                        </a>

                        <a href="https://wa.me/8801978855506" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 text-white transition-colors hover:text-emerald-400">
                            <svg class="h-3.5 w-3.5 shrink-0 fill-current text-emerald-400" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            <span>01978-855506</span>
                        </a>

                        <a href="mailto:info@hbdservices.com"
                            class="inline-flex items-center gap-1.5 text-white transition-colors hover:text-accent-one-300">
                            <iconify-icon icon="lucide:mail" class="text-sm text-accent-one-400"></iconify-icon>
                            <span>info@hbdservices.com</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>


        <!-- Bottom Bar -->
        <div
            class="pt-6 sm:pt-8 border-t border-neutral-600 flex flex-col md:flex-row justify-between items-center text-neutral-500 gap-4">
            <p class="text-center text-xs sm:text-sm order-2 md:order-1">
                Copyright © 2026 HBD Services. All Rights Reserved.
            </p>
            <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 text-sm font-semibold order-1 md:order-2">
                <a href="{{ route('privacy-policy') }}" class="hover:text-neutral-300 transition">Privacy Policy</a>
                <a href="{{ route('terms-conditions') }}" class="hover:text-neutral-300 transition">Terms of
                    Service</a>
                <a href="{{ route('contact') }}" class="hover:text-neutral-300 transition">Contact Us</a>
            </div>
        </div>
    </div>
</footer>
