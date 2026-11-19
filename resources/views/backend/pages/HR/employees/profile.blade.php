@extends('backend.layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl space-y-8 pb-16">

        {{-- Flash / validation feedback --}}
        @if (session('success') || session('error') || $errors->any())
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition
                class="fixed right-5 top-3 z-[99999] w-full max-w-sm space-y-3">
                @if (session('success'))
                    <div class="relative">
                        <button @click="show = false" aria-label="Dismiss"
                            class="absolute right-3 top-3 z-10 text-neutral-500 hover:text-neutral-700">✕</button>
                        <x-ui.alert variant="success" title="" message="{{ session('success') }}" />
                    </div>
                @endif

                @if (session('error'))
                    <div class="relative">
                        <button @click="show = false" aria-label="Dismiss"
                            class="absolute right-3 top-3 z-10 text-neutral-500 hover:text-neutral-700">✕</button>
                        <x-ui.alert variant="error" title="" message="{{ session('error') }}" />
                    </div>
                @endif

                @if ($errors->any())
                    <div class="relative">
                        <button @click="show = false" aria-label="Dismiss"
                            class="absolute right-3 top-3 z-10 text-neutral-500 hover:text-neutral-700">✕</button>
                        <x-ui.alert variant="error" title="Please check the highlighted fields"
                            message="{{ $errors->first() }}" />
                    </div>
                @endif
            </div>
        @endif

        {{-- Hero Banner / Header Card --}}
        @include('backend.pages.HR.employees.partial.header')

        {{-- Sticky Section Navigation Tabs --}}
        <div
            class="sticky top-19 z-50 backdrop-blur-md bg-white/80 dark:bg-neutral-900/80 p-1.5 rounded-xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <nav class="flex flex-wrap gap-1.5" x-data="{ activeTab: 'profile' }">
                @php
                    $tabs = [
                        ['id' => 'profile', 'icon' => 'lucide:user', 'label' => 'Profile'],
                        ['id' => 'personal', 'icon' => 'lucide:id-card', 'label' => 'Personal'],
                        ['id' => 'information', 'icon' => 'lucide:briefcase', 'label' => 'Information'],
                        ['id' => 'address', 'icon' => 'lucide:map-pin', 'label' => 'Address'],
                        ['id' => 'bank', 'icon' => 'lucide:landmark', 'label' => 'Bank Accounts'],
                        ['id' => 'documents', 'icon' => 'lucide:file-text', 'label' => 'Documents'],
                    ];
                @endphp

                @foreach ($tabs as $tab)
                    <a href="#{{ $tab['id'] }}" @click="activeTab = '{{ $tab['id'] }}'"
                        :class="activeTab === '{{ $tab['id'] }}' ? 'bg-brand-600 text-white shadow-sm' :
                            'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                        class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                        <iconify-icon icon="{{ $tab['icon'] }}"
                            class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                            :class="activeTab === '{{ $tab['id'] }}' ? 'text-white' : ''"></iconify-icon>
                        <span>{{ $tab['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Main Sections --}}
        <div class="space-y-8">
            <section id="profile" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.profile-update')
            </section>

            <section id="personal" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.personal', ['selfService' => true])
            </section>

            <section id="information" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.employee-information')
            </section>

            <section id="address" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.address')
            </section>

            <section id="bank" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.bank_accounts')
            </section>

            <section id="documents" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.employee_documents')
            </section>
        </div>
    </div>
@endsection
