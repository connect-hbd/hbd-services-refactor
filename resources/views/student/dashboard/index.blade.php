@extends('student.layouts.app')

@section('content')
    <div class="sm:rounded-2xl sm:border border-neutral-200 bg-white px-5 py-8 sm:px-8 sm:py-10 lg:px-10 mt-4 min-h-180">

        @if (session('message'))
            <div class="mb-5">
                <x-ui.alert variant="success" title="" :message="session('message')" />
            </div>
        @endif

        hfghfggf

    </div>
@endsection
