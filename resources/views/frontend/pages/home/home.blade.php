@extends('frontend.layouts.app')

@section('content')
    @include('frontend.pages.home.sections.hero')

    @include('frontend.pages.home.sections.journing')

    @include('frontend.pages.common-section.destination', ['destinations' => $destinations])

    <section class="relative overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    @include('frontend.pages.common-section.service-section')

    </section>


    @include('frontend.pages.home.sections.language-academy')

    @include('frontend.pages.home.sections.digital-platform')

    @include('frontend.pages.home.sections.achievers')

    @include('frontend.pages.home.sections.certification-awards')

    @include('frontend.pages.home.sections.google-reviews')

    @include('frontend.pages.common-section.university-partners')

    @include('frontend.pages.common-section.cta-section')
@endsection

{{-- Scroll reveal script is now loaded globally in frontend.layouts.app --}}
