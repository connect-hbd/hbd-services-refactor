<!-- FOOTER -->
<footer class="bg-neutral-900 text-neutral-400 pt-10 sm:pt-12 md:pt-16 pb-8 border-t border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Main Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 sm:gap-10 mb-10 sm:mb-12">

            <!-- Brand + Social -->
            <div class="lg:col-span-4 -mt-10">
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
                <div
                    class="mb-4 sm:mb-5 flex flex-col items-start gap-2.5 rounded-xl border border-white/10 bg-white/[0.04] p-4 backdrop-blur-sm transition-colors duration-200 hover:border-accent-one-400/30 hover:bg-white/[0.06]">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.18em] text-accent-one-400">
                        Quick Contact
                    </h4>
                    <a href="mailto:info@hbdservices.com"
                        class="group flex items-center gap-2.5 text-sm font-semibold text-white transition-colors hover:text-accent-one-300">
                        <iconify-icon icon="lucide:mail"
                            class="shrink-0 text-base text-accent-one-400 transition-transform duration-200 group-hover:scale-110"></iconify-icon>
                        <span>info@hbdservices.com</span>
                    </a>
                </div>

                <h4 class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-white flex items-center justify-center sm:items-start sm:justify-start">
                    Follow Us
                </h4>
                <div class="flex space-x-3 items-center justify-center sm:justify-start">
                    <a href="https://www.facebook.com/hbdservices"
                        class="w-9 h-9 sm:w-10 sm:h-10 bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-400 hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-facebook-f text-sm sm:text-base"></i>
                    </a>
                    <a href="https://twitter.com/hbdservicesbd"
                        class="w-9 h-9 sm:w-10 sm:h-10 bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-400 hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-twitter text-sm sm:text-base"></i>
                    </a>
                    <a href="https://www.instagram.com/hbd_services_bd"
                        class="w-9 h-9 sm:w-10 sm:h-10 bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-400 hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-instagram text-sm sm:text-base"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/hbdservices/"
                        class="w-9 h-9 sm:w-10 sm:h-10 bg-neutral-800 rounded-lg flex items-center justify-center text-neutral-400 hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-brands fa-linkedin-in text-sm sm:text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="lg:col-span-3 flex flex-col items-center justify-center sm:items-start sm:justify-start">
                <h4 class="text-white text-sm sm:text-base font-bold uppercase tracking-wider mb-3 sm:mb-4">Quick Links
                </h4>
                <ul class="space-y-2 text-sm sm:text-[15px] text-center sm:text-left">
                    <li><a href="{{ route('about') }}" class="hover:text-brand-400 transition">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-brand-400 transition">Contact Us</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition">Our Experts</a></li>
                    <li><a href="{{ route('events') }}" class="hover:text-brand-400 transition">Explore Events</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition">Our Expo</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition">Book Appointment</a></li>
                    <li><a href="https://hbdlanguageacademy.com/" class="hover:text-brand-400 transition">IELTS/PTE
                            Registration</a></li>

                    {{-- <li><a href="{{ route('referral') }}" class="hover:text-brand-400 transition">Referral Program</a></li> --}}
                </ul>
            </div>

            <!-- Quick Links -->
            {{-- <div class="flex flex-col items-center justify-center sm:items-start sm:justify-start">
        <h4 class="text-white text-sm sm:text-base font-bold uppercase tracking-wider mb-3 sm:mb-4">Quick Links</h4>
        <ul class="space-y-2 text-sm sm:text-[15px] text-center sm:text-left">
          <li><a href="#" class="hover:text-brand-400 transition">Scholarships</a></li>
          <li><a href="{{ route('universities') }}" class="hover:text-brand-400 transition">Universities</a></li>
          <li><a href="#" class="hover:text-brand-400 transition">Counsellors</a></li>
          <li><a href="{{ route('events') }}" class="hover:text-brand-400 transition">Events</a></li>
        </ul>
      </div> --}}

            <!-- Resources -->
            <div class="lg:col-span-5">

                <div class="flex items-start flex-col gap-2 rounded-xl border border-white/10 bg-white/4 p-4">

                    <div class="flex gap-2 items-center w-full">

                        <div class="flex shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <img src="https://flagcdn.com/w40/au.png" alt="Australia flag" width="40" height="30"
                                class="h-5 w-7 shrink-0 rounded-sm object-cover" loading="lazy">
                        </div>
                        <h4
                            class="w-full flex items-center justify-between text-[13px] font-bold uppercase tracking-wider text-white">
                            <span>
                                Australia Office
                            </span>
                            <span
                                class="ml-auto rounded-full bg-accent-one-500/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-accent-one-400">
                                Head Office
                            </span>
                        </h4>

                    </div>

                    <div class="mt-1">
                        <div class="flex items-start gap-1 text-sm leading-relaxed text-neutral-500 font-medium">
                            <iconify-icon icon="lucide:map-pin"
                                class="mt-1 shrink-0 text-base text-neutral-400"></iconify-icon>
                            <span>Unit 127 (Level 8),Museum Tower, 267-277 Castlereagh St, Sydney NSW,
                                Australia-2000</span>
                        </div>

                        <div class="flex items-center gap-4 border-t border-white/10 pt-2 mt-3">
                            <a href="tel:+61 2 9740 8672"
                                class="mt-1 inline-flex items-center gap-2 text-sm font-bold text-white transition-colors hover:text-white/45">

                                <iconify-icon icon="lucide:phone" class="text-sm"></iconify-icon>
                                + 61 2 9740 8672
                            </a>
                            <a href="tel:+61 2 9740 8672"
                                class="mt-1 inline-flex items-center gap-2 text-sm font-bold text-white transition-colors hover:text-white/45">

                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                + 61 2 9740 8672
                            </a>
                        </div>

                    </div>

                </div>

                {{-- Address Card --}}
                <div class="mt-4 flex items-start flex-col gap-2 rounded-xl border border-white/10 bg-white/4 p-4">

                    <div class="flex gap-2 items-center">

                        <div class="flex shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <img src="https://flagcdn.com/w40/bd.png" alt="Bangladesh flag" width="40"
                                height="30" class="h-5 w-7 shrink-0 rounded-sm object-cover" loading="lazy">
                        </div>
                        <span class="min-w-0 block text-[13px] font-bold uppercase tracking-wider text-white">
                            Bangladesh Office
                        </span>
                    </div>



                    <div class="mt-1">

                        <div class="flex items-start gap-1 text-sm leading-relaxed text-neutral-500 font-medium">
                            <iconify-icon icon="lucide:map-pin"
                                class="mt-1 shrink-0 text-base text-neutral-400"></iconify-icon>
                            <span>Standard Center, 27/1, 7th Floor, New Eskaton Road, Dhaka-1000</span>
                        </div>

                        <div class="flex items-center gap-4 border-t border-white/10 pt-2 mt-3">
                            <a href="tel:09617990099"
                                class="mt-1 inline-flex items-center gap-2 text-sm font-bold text-white transition-colors hover:text-white/45">

                                <iconify-icon icon="lucide:phone" class="text-sm"></iconify-icon>
                                09617-990099
                            </a>
                            <a href="tel:01978855506"
                                class="mt-1 inline-flex items-center gap-2 text-sm font-bold text-white transition-colors hover:text-white/45">

                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                01978-855506
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Bottom Bar -->
        <div
            class="pt-6 sm:pt-8 border-t border-neutral-800 flex flex-col md:flex-row justify-between items-center text-neutral-500 gap-4">
            <p class="text-center text-xs sm:text-sm order-2 md:order-1">
                Copyright © 2026 HBD Services. All Rights Reserved.
            </p>
            <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 text-sm font-semibold order-1 md:order-2">
                <a href="{{ route('privacy-policy') }}" class="hover:text-neutral-300">Privacy Policy</a>
                <a href="{{ route('terms-conditions') }}" class="hover:text-neutral-300">Terms of Service</a>
                <a href="{{ route('contact') }}" class="hover:text-neutral-300">Contact Us</a>
            </div>
        </div>
    </div>
</footer>
