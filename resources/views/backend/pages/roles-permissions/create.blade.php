@extends('backend.layouts.app')

@section('content')
    <div class="">
        <form method="POST" action="{{ role_route('role.roles-permissions.store') }}" class="space-y-6">
            @csrf
            <div class="flex items-center justify-between sticky top-16 z-10 rounded-lg border-b border-neutral-200  bg-white p-5 dark:border-neutral-800 dark:bg-neutral-900 ">
                <div>
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white/90">Create Role Permission</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Roles are dynamic containers. Access is decided
                        by permissions.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ role_route('role.roles-permissions.index') }}"
                        class="flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm text-neutral-700 dark:border-neutral-700 dark:text-neutral-300">
                        <span>Cancel</span>
                        <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 flex items-center ">
                        Save Role Permission
                    </button>
                  
                </div>
            </div>
            @include('backend.pages.roles-permissions.partials.form', ['role' => null])
        </form>
    </div>
@endsection


                    
