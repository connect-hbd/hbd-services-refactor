 {{-- ── Personal Information ────────────────────────────────────────── --}}
 <div class="space-y-4">
    
      <div class="flex items-center justify-between mb-4">
                        <label class="text-base font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold">1</span>
                            Personal Information 
                        </label>
                    </div>

     <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
         <div>
             <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                 Full Name
             </label>
             <input type="text" name="name" value=""
                 class="w-full h-10 px-3.5 text-sm rounded-lg border border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 placeholder-neutral-400 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                 placeholder="John Doe">
             @error('name')
                 <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                     <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     {{ $message }}
                 </p>
             @enderror
         </div>

         <div>
             <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                 Phone Number
             </label>
             <input type="text" name="phone" value=""
                 class="w-full h-10 px-3.5 text-sm rounded-lg border border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80 text-neutral-800 dark:text-neutral-200 placeholder-neutral-400 focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                 placeholder="01978855506">
             @error('phone')
                 <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                     <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     {{ $message }}
                 </p>
             @enderror
         </div>

         <div>
             <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                 Email Address
             </label>
             <div class="relative">
                 <input type="email" value="" readonly
                     class="w-full h-10 pl-3.5 pr-20 text-sm rounded-lg border border-neutral-200/80 bg-neutral-100/80 dark:bg-neutral-800/80 dark:border-neutral-700 text-neutral-500 dark:text-neutral-400 cursor-not-allowed selection:bg-none"
                     placeholder="student@example.com">
                 <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                     <span
                         class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-neutral-200/70 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                         <svg class="w-3 h-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                         </svg>
                         Locked
                     </span>
                 </div>
             </div>
         </div>

         {{-- Gender --}}
         <div>
             <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                 Gender <span class="text-rose-500">*</span>
             </label>
             <div class="relative">
                 <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-neutral-400">
                     <iconify-icon icon="lucide:users" class="text-sm"></iconify-icon>
                 </div>
                 <select name="gender"
                     class="w-full h-10 pl-10 pr-10 text-sm rounded-lg border appearance-none bg-none
                                {{ $errors->has('gender') ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20' : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                focus:bg-white dark:focus:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                     <option value="">Select Gender</option>
                     <option value="Female">Female</option>
                     <option value="Male">Male</option>
                     <option value="Other">Other</option>
                 </select>
                 <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-neutral-400">
                     <iconify-icon icon="lucide:chevron-down" class="text-xl"></iconify-icon>
                 </div>
             </div>
             @error('gender')
                 <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
             @enderror
         </div>
     </div>
     <div x-data="{
         contacts: {
             phones: [
                 { value: '', is_whatsapp: false },
                 { value: '', is_whatsapp: false }
             ],
             emails: [
                 { value: '' },
                 { value: '' }
             ]
         }
     }">

         <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

             {{-- Alternative Phone 1 --}}
             <div>
                 <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                     Alternative Phone
                 </label>
                 <input type="text" name="alt_phone[0][value]"
                     class="w-full h-10 px-3.5 rounded-lg text-sm border
                                {{ $errors->has('alt_phone.0.value')
                                    ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20'
                                    : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                placeholder:text-neutral-400
                                focus:bg-white dark:focus:bg-neutral-900
                                focus:outline-none focus:ring-2 focus:ring-brand-500/20
                                focus:border-brand-500 transition-all"
                     placeholder="Enter alternative phone...">

                 <label class="mt-2 inline-flex cursor-pointer items-center gap-2">
                     <input type="checkbox" name="alt_phone[0][is_whatsapp]" value="0"
                         x-model="contacts.phones[0].is_whatsapp"
                         class="h-4 w-4 rounded border-neutral-300 text-brand-500 focus:ring-brand-500 dark:border-neutral-700 dark:bg-neutral-900">
                     <span class="text-xs font-medium text-neutral-600 dark:text-neutral-400">
                         WhatsApp
                     </span>
                 </label>

                 @error('alt_phone.0.value')
                     <p class="mt-1.5 text-xs font-medium text-rose-600">
                         {{ $message }}
                     </p>
                 @enderror
             </div>
             {{-- Alternative Email 1 --}}
             <div>
                 <label class="block mb-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                     Alternative Email
                 </label>
                 <input type="text" name="alt_email[0]"
                     class="w-full h-10 px-3.5 rounded-lg text-sm border
                                {{ $errors->has('alt_email.0')
                                    ? 'border-rose-400 bg-rose-50/30 dark:bg-rose-950/20'
                                    : 'border-neutral-200 bg-neutral-50/40 dark:bg-neutral-800/50 dark:border-neutral-700/80' }}
                                text-neutral-800 dark:text-neutral-200
                                placeholder:text-neutral-400
                                focus:bg-white dark:focus:bg-neutral-900
                                focus:outline-none focus:ring-2 focus:ring-brand-500/20
                                focus:border-brand-500 transition-all"
                     placeholder="Enter alternative email...">

                 @error('alt_email.0')
                     <p class="mt-1.5 text-xs font-medium text-rose-600">
                         {{ $message }}
                     </p>
                 @enderror
             </div>
         </div>
     </div>
 </div>
