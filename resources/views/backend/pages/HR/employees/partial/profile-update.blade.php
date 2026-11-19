<div class="overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">

    {{-- Section Header --}}
    <div class="border-b border-neutral-100 bg-neutral-50/50 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-800/50">
        <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Profile & Password</h2>
        <p class="text-xs text-neutral-500 dark:text-neutral-400">Update your profile photo and login password.</p>
    </div>

    <form action="{{ role_route('role.profile.update') }}" method="POST"
        enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Avatar -->
            <div class="lg:col-span-1">
                <label class="block mb-3 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Profile Photo
                </label>
                <div class="flex flex-col items-center">
                    <label class="relative group cursor-pointer">
                        <!-- Avatar Container -->
                        <div class="relative w-36 h-36 rounded-full overflow-hidden ring-4 ring-neutral-100 group-hover:ring-brand-100 transition-all duration-300">
                            <img id="avatarPreview"
                                src="{{ $employee->user?->avatar ? asset($employee->user->avatar) : asset('images/user/owner.png') }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                alt="User Profile" />

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-neutral-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-white text-xs font-medium">Change Photo</span>
                            </div>
                        </div>

                        <!-- Camera Badge -->
                        <div class="absolute bottom-1 right-1 flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-white shadow-lg ring-2 ring-white group-hover:bg-brand-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                        </div>

                        <input type="file" name="avatar" accept="image/*" class="hidden"
                            onchange="previewAvatar(event)">
                    </label>

                    <p class="mt-3 text-xs text-neutral-400">JPG, PNG or GIF. Max 2MB.</p>

                    @error('avatar')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h3 class="flex items-center gap-2 text-sm font-bold text-neutral-900 dark:text-white uppercase tracking-wide mb-4">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Change Password
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Current Password
                            </label>
                            <input type="password" name="current_password"
                                class="w-full rounded-lg h-11 border border-neutral-300 bg-white px-4 text-sm text-neutral-800 placeholder:text-neutral-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                placeholder="Enter current password">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                New Password
                            </label>
                            <input type="password" name="password"
                                class="w-full rounded-lg h-11 border border-neutral-300 bg-white px-4 text-sm text-neutral-800 placeholder:text-neutral-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                placeholder="Enter new password">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-lg h-11 border border-neutral-300 bg-white px-4 text-sm text-neutral-800 placeholder:text-neutral-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                placeholder="Confirm new password">
                        </div>

                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">
                    <button type="button" onclick="window.location.reload()"
                        class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg shadow-sm hover:bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewAvatar(event) {
        const preview = document.getElementById('avatarPreview');

        if (event.target.files && event.target.files[0]) {
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    }
</script>
