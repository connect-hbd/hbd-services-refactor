@extends('student.layouts.app')

@section('content')
    <main class="min-h-screen bg-slate-50/80">
        <div class="mx-auto max-w-4xl px-4 py-6 sm:py-10 sm:px-6 md:py-14">

            {{-- Header --}}
            <header class="mb-8 text-center sm:mb-10">
                <span
                    class="mb-3 inline-flex uppercase items-center gap-2 rounded-full border border-brand-200/80 bg-white px-3.5 py-1.5 text-xs font-semibold tracking-wide text-brand-600 shadow-2xs">
                    <i class="fa-solid fa-graduation-cap"></i>
                    HBD Student Services
                </span>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Start Your Study Abroad Journey
                </h1>

                <p class="mx-auto mt-2.5 max-w-xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    Choose the service that matches your goals.
                    Our team will guide you through your next step.
                </p>
            </header>

            {{-- Information --}}
            <div
                class="mb-6 flex items-start gap-2 rounded-2xl border border-amber-200/80 bg-amber-50/70 p-4 sm:mb-8 sm:p-5">
                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <i class="fa-solid fa-circle-info text-sm"></i>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Important Information
                    </h2>

                    <p class="mt-1 text-sm leading-relaxed text-slate-700">
                        এই ফর্মটি বিদেশে উচ্চ শিক্ষায় আগ্রহী শিক্ষার্থীদের জন্য।
                        সকল তথ্য পাসপোর্ট ও একাডেমিক ডকুমেন্ট অনুযায়ী সঠিকভাবে প্রদান করুন।
                        ভুল তথ্য দিলে আবেদন বাতিল হতে পারে।
                    </p>
                </div>
            </div>

            {{-- Service Selection --}}
            <section class="rounded-3xl border border-slate-200/90 bg-white p-4 shadow-xs sm:p-6">


                <div class="mb-4 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/50 dark:text-brand-400">
                        <iconify-icon icon="lucide:compass" class="text-xl text-slate-400"
                            aria-hidden="true"></iconify-icon>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold tracking-tight text-neutral-900 dark:text-white">
                            Choose Your Service
                        </h2>
                        <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">
                            Select one of the following services to continue.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 sm:gap-5">

                    {{-- Study Abroad --}}
                    <label
                        class="group relative block cursor-pointer rounded-xl border border-slate-200 bg-white p-3 transition duration-150 hover:border-brand-300 hover:bg-slate-50/50 has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/40 has-[:checked]:ring-2 has-[:checked]:ring-brand-600/20 sm:p-4">
                        <input type="radio" name="service" value="study-abroad" class="sr-only">

                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-has-[:checked]:bg-brand-600 group-has-[:checked]:text-white">
                                    <i class="fa-solid fa-plane-departure text-base"></i>
                                </div>

                                <h3 class="text-base font-semibold text-slate-900 sm:text-lg">
                                    Study Abroad
                                </h3>
                            </div>

                            <span
                                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-300 text-[10px] text-transparent transition group-has-[:checked]:border-brand-600 group-has-[:checked]:bg-brand-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        </div>

                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                            Explore international education opportunities
                            and get professional guidance for your higher
                            studies abroad.
                        </p>

                          <div
                            class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500 transition-colors group-has-[:checked]:border-brand-200 group-has-[:checked]:text-brand-700">

                            <i class="fa-solid fa-globe"></i>

                            <span>International education</span>

                        </div>
                    </label>

                    {{-- English Proficiency --}}
                    <label
                        class="group relative block cursor-pointer rounded-xl border border-slate-200 bg-white p-3 transition duration-150 hover:border-emerald-300 hover:bg-slate-50/50 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 has-[:checked]:ring-2 has-[:checked]:ring-emerald-600/20 sm:p-4">
                        <input type="radio" name="service" value="english-proficiency" class="sr-only">

                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-has-[:checked]:bg-emerald-600 group-has-[:checked]:text-white">
                                    <i class="fa-solid fa-book-open-reader text-base"></i>
                                </div>

                                <h3 class="text-base font-semibold text-slate-900 sm:text-lg">
                                    English Proficiency
                                </h3>
                            </div>

                            <span
                                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-slate-300 text-[10px] text-transparent transition group-has-[:checked]:border-emerald-600 group-has-[:checked]:bg-emerald-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        </div>

                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                            Improve your English language skills and
                            prepare for international English proficiency
                            examinations.
                        </p>
                      
                        <div
                                    class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500 transition-colors group-has-[:checked]:border-emerald-200 group-has-[:checked]:text-emerald-700">

                                    <i class="fa-solid fa-language"></i>

                                    <span>English language preparation</span>

                                </div>
                    </label>

                </div>

                {{-- Footer Actions --}}
                <div
                    class="mt-3 flex flex-col gap-2 border-t border-slate-100 pt-2 sm:mt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="flex items-center gap-2 text-xs text-slate-500">
                        <i class="fa-solid fa-shield-halved text-slate-400"></i>
                        Your information will be handled securely.
                    </p>

                    <div class="flex flex-col sm:items-end mt-4 sm:mt-0">
                        <p id="service-error" class="mb-2 hidden text-xs font-medium text-rose-600" role="alert">
                            Please select a service before continuing.
                        </p>

                        <button type="button" onclick="continueService()"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2.5 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-xs transition hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 active:scale-[0.99] sm:w-auto">
                            Continue
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

            </section>

        </div>
    </main>

    <script>
        function continueService() {
            const selected = document.querySelector('input[name="service"]:checked');
            const error = document.getElementById('service-error');

            if (!selected) {
                error.classList.remove('hidden');
                return;
            }

            error.classList.add('hidden');

            const routes = {
                'study-abroad': '/student/study-abroad',
                'english-proficiency': '/student/english-proficiency'
            };

            window.location.href = routes[selected.value];
        }

        document.querySelectorAll('input[name="service"]').forEach((input) => {
            input.addEventListener('change', () => {
                document.getElementById('service-error').classList.add('hidden');
            });
        });
    </script>
@endsection
