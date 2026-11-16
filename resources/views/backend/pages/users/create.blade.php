@extends('backend.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-white/90">Create User</h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Assign one primary role and any additional role
                containers.</p>
        </div>

        <form method="POST" action="{{ role_route('role.users.store') }}" class="space-y-6">
            @csrf
            @include('backend.pages.users.partials.form', ['user' => null])

            <div class="flex items-center justify-end gap-3">
                <button type="submit"
                    class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    Save User
                </button>
                <a href="{{ role_route('role.users.index') }}"
                    class="rounded-lg border border-neutral-300 px-4 py-2 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
