@extends('frontend.layouts.app')

@section('content')
    <section class="py-8 lg:py-12">
        @include('frontend.pages.common-section.service-section')

    </section>

     @include('frontend.pages.common-section.cta-section')
@endsection

{{-- Scroll reveal script is now loaded globally in frontend.layouts.app --}}
