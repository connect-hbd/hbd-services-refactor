@extends('student.layouts.app')

@section('content')
    <main class="min-h-screen bg-slate-100/70">

        <div class="mx-auto max-w-7xl py-5 sm:py-8 px-0 sm:px-6 lg:px-8 ">

            {{-- Welcome Section --}}
            <section class="hidden sm:block mb-8 overflow-hidden sm:rounded-2xl bg-blue-900 shadow-lg shadow-blue-950/10">

                <div class="relative px-6 py-8 sm:px-10 sm:py-10">

                    {{-- Background decoration --}}
                    <div aria-hidden="true"
                        class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full border-[40px] border-white/5">
                    </div>

                    <div aria-hidden="true"
                        class="pointer-events-none absolute -bottom-32 right-32 h-64 w-64 rounded-full bg-blue-500/10">
                    </div>

                    <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">

                        <div class="max-w-2xl">

                            <div
                                class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100">
                                <i class="fa-solid fa-house text-[11px]"></i>
                                Student Dashboard
                            </div>

                            <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">
                                Welcome to your student portal
                            </h2>

                            <p class="mt-3 max-w-xl text-sm leading-6 text-blue-100 sm:text-base sm:leading-7">
                                Your journey to a brighter future starts here.
                                Choose a service to explore your opportunities
                                and get guidance from our team.
                            </p>

                        </div>

                        {{-- Application Progress --}}
                        <div class="w-full rounded-xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm lg:w-64">

                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-semibold text-white">
                                    Application journey
                                </span>

                                <i class="fa-solid fa-route text-blue-200"></i>
                            </div>

                            <div class="mt-4 flex items-center gap-2">

                                <span class="h-2 flex-1 rounded-full bg-blue-300"></span>
                                <span class="h-2 flex-1 rounded-full bg-white/20"></span>
                                <span class="h-2 flex-1 rounded-full bg-white/20"></span>

                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-blue-100">
                                    Step 1 of 3
                                </span>

                                <span class="text-xs font-semibold text-white">
                                    Get started
                                </span>
                            </div>

                        </div>

                    </div>
                </div>
            </section>

            {{-- Main Content --}}
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_290px] lg:items-start">

                <div class="min-w-0">

                    {{-- Service Selection --}}
                    <section class="overflow-hidden sm:rounded-2xl border border-slate-200 bg-white shadow-sm">

                        {{-- Section Header --}}
                        <div class="border-b border-slate-200 px-5 py-5 sm:px-7 sm:py-6 flex items-center gap-2">

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

                        {{-- Service Options --}}
                        <div class="p-5 sm:p-7">

                            <fieldset>

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

                                        <p class="mt-1 sm:mt-3 text-[13px] sm:text-sm leading-relaxed text-slate-500">
                                            Explore international education opportunities
                                            and get professional guidance for your higher
                                            studies abroad.
                                        </p>

                                        <div
                                            class="mt-1 sm:mt-3 flex items-center gap-2 border-t border-slate-100 pt-2 text-xs font-medium text-slate-500 transition-colors group-has-[:checked]:border-brand-200 group-has-[:checked]:text-brand-700">

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

                                        <p class="mt-1 sm:mt-3 text-[13px] sm:text-sm leading-relaxed text-slate-500">
                                            Improve your English language skills and
                                            prepare for international English proficiency
                                            examinations.
                                        </p>

                                        <div
                                            class="mt-1 sm:mt-3 flex items-center gap-2 border-t border-slate-100 pt-2 text-xs font-medium text-slate-500 transition-colors group-has-[:checked]:border-emerald-200 group-has-[:checked]:text-emerald-700">

                                            <i class="fa-solid fa-language"></i>

                                            <span>English language preparation</span>

                                        </div>
                                    </label>

                                </div>
                            </fieldset>

                            {{-- Error Message --}}
                            <p id="service-error"
                                class="mt-5 hidden items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
                                role="alert" aria-live="polite">

                                <i class="fa-solid fa-circle-exclamation"></i>

                                Please select a service before continuing.

                            </p>

                            {{-- Continue Button --}}
                            <div
                                class="mt-6 flex flex-col gap-4 border-slate-100 sm:flex-row sm:items-center sm:justify-between">

                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fa-solid fa-shield-halved text-slate-400"></i>
                                    <span>Your information is handled securely.</span>
                                </div>

                                <button type="button" onclick="continueService()"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2.5 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-xs transition hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 active:scale-[0.99] sm:w-auto">
                                    Continue
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </button>

                            </div>

                        </div>

                    </section>

                </div>

                {{-- Right Sidebar --}}
                <aside class="space-y-5">

                    {{-- Important Information --}}
                    <section class="sm:rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <i class="fa-solid fa-circle-info text-lg"></i>
                            </div>

                            <h2 class="text-sm font-bold text-slate-900">
                                Important Information
                            </h2>

                        </div>

                        <p class="mt-4 text-sm leading-6 text-slate-600">
                            এই ফর্মটি বিদেশে উচ্চশিক্ষায় আগ্রহী শিক্ষার্থীদের জন্য।
                            সকল তথ্য পাসপোর্ট ও একাডেমিক ডকুমেন্ট অনুযায়ী সঠিকভাবে প্রদান করুন।
                            ভুল তথ্য দিলে আবেদন বাতিল হতে পারে।
                        </p>

                    </section>


                    {{-- Support Card --}}
                    <section class="sm:rounded-2xl border border-blue-100 bg-blue-50/70 p-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                                <i class="fa-solid fa-headset text-lg"></i>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-slate-900">
                                    Need assistance?
                                </h2>

                                <p class="mt-1 text-xs text-slate-600">
                                    Our team is here to help.
                                </p>
                            </div>

                        </div>

                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            If you are unsure which service to choose,
                            contact our support team for guidance.
                        </p>

                    </section>

                </aside>

            </div>

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
