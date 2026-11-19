@extends('frontend.layouts.app')

@section('title', $counsellor['user']['name'] ?? 'Counsellor')


@section('content')

    <section class="mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Counsellor Profile Details (4 Cols) -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-8 border border-neutral-100 shadow-sm space-y-6">
                @include('frontend.pages.counsellors.profile', ['counsellor' => $counsellor])
            </div>

            <!-- RIGHT COLUMN: Booking Form & Selection (8 Cols) -->
            <div class="lg:col-span-8 ">
                @include('frontend.pages.counsellors.appointment', ['counsellor' => $counsellorRecord])

            </div>

        </div>
        </div>
    </section>
@endsection
