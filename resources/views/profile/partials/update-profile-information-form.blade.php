<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- حقل رفع وعرض الصورة الشخصية المطور بالشفافية الكاملة لمنع أي تضارب -->
        <div>
            <x-input-label :value="__('الصورة الشخصية')" />
            
            <div class="flex items-center gap-6 mt-3">
                <!-- حاوية الصورة الشخصية التفاعلية -->
                <div class="relative w-20 h-20 group shrink-0 select-none">
                    <!-- 1. عرض صورة الـ Avatar الحالية -->
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="w-full h-full rounded-full border-2 border-indigo-500 shadow-md object-cover transition-all duration-300 group-hover:scale-105 dark:border-indigo-400">
                    
                    <!-- 2. طبقة تلميح نصي تظهر عند تمرير الماوس فوق الصورة -->
                    <div class="absolute inset-0 bg-black/40 text-white text-[10px] font-bold rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center pointer-events-none transition-opacity duration-200">
                        🔄 تغيير
                    </div>

                    <!-- 3. حقل الرفع الفعلي تم تكبيره ليغطي الدائرة بالكامل وجعله شفافاً ومخفياً تماماً -->
                    <!-- هذه الطريقة تضمن فتح نافذة الملفات عند النقر في أي مكان داخل الدائرة على الهاتف أو الكمبيوتر -->
                    <input id="avatar" name="avatar" type="file" accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20 rounded-full" />
                </div>
                
                <div class="flex-1 space-y-1">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">تعديل الصورة الشخصية</p>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">انقر مباشرة فوق الدائرة الزرقاء لاختيار صورة جديدة من جهازك (الصيغ المدعومة: png, jpg بحد أقصى 2 ميجابايت).</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <!-- حقل الاسم الافتراضي -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- حقل البريد الإلكتروني الافتراضي -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- أزرار الحفظ الإفتراضية -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
