@extends('backend.layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl space-y-8 pb-16">

        {{-- Top Navigation / Back --}}
        <div class="flex items-center justify-between">
            <a href=""
                class="inline-flex items-center text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Employees
            </a>
          
        </div>

        {{-- Hero Banner / Header Card --}}
     @include('backend.pages.HR.employees.partial.header')

        {{-- Sticky Section Navigation Tabs --}}
        <div
            class="sticky top-19 z-50 backdrop-blur-md bg-white/80 dark:bg-neutral-900/80 p-1.5 rounded-xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <nav class="flex flex-wrap gap-1.5" x-data="{ activeTab: 'personal' }">
                {{-- Personal --}}
                <a href="#personal" @click="activeTab = 'personal'"
                    :class="activeTab === 'personal' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:user"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'personal' ? 'text-white' : ''"></iconify-icon>
                    <span>Personal</span>
                </a>

                {{-- Address --}}
                <a href="#address" @click="activeTab = 'address'"
                    :class="activeTab === 'address' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:map-pin"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'address' ? 'text-white' : ''"></iconify-icon>
                    <span>Address</span>
                </a>

                {{-- Employment --}}
                <a href="#employment" @click="activeTab = 'employment'"
                    :class="activeTab === 'employment' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:briefcase"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'employment' ? 'text-white' : ''"></iconify-icon>
                    <span>Employment</span>
                </a>

                {{-- Salary --}}
                <a href="#salary" @click="activeTab = 'salary'"
                    :class="activeTab === 'salary' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:wallet"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'salary' ? 'text-white' : ''"></iconify-icon>
                    <span>Salary</span>
                </a>

                {{-- Bank Accounts --}}
                <a href="#bank" @click="activeTab = 'bank'"
                    :class="activeTab === 'bank' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:landmark"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'bank' ? 'text-white' : ''"></iconify-icon>
                    <span>Bank Accounts</span>
                </a>

                {{-- Documents --}}
                <a href="#documents" @click="activeTab = 'documents'"
                    :class="activeTab === 'documents' ? 'bg-brand-600 text-white shadow-sm' :
                        'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800'"
                    class="flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-lg transition-all group">
                    <iconify-icon icon="lucide:file-text"
                        class="text-[16px] text-neutral-400 transition-colors group-hover:text-brand-600"
                        :class="activeTab === 'documents' ? 'text-white' : ''"></iconify-icon>
                    <span>Documents</span>
                </a>
            </nav>
        </div>

        {{-- Main Sections --}}
        <div class="space-y-8">
            <section id="personal" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.personal')
            </section>
            <section id="address" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.address')
            </section>

            <section id="employment" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.employeement-edit')
            </section>

            <section id="salary" class="scroll-mt-31">
                @include('backend.pages.HR.employees.partial.salary')
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
