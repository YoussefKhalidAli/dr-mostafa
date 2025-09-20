<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('معلومات الحساب') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("قم بتحديث بياناتك الشخصية والبريد الإلكتروني.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-lg" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 rounded-lg" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('البريد الإلكتروني غير مفعل.') }}

                        <button form="send-verification" class="underline text-sm text-blue-600 hover:text-blue-800">
                            {{ __('اضغط هنا لإعادة إرسال رسالة التفعيل.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تفعيل جديد لبريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#2563eb] hover:bg-[#1e3a8a] text-white px-4 py-2 rounded-lg">
                {{ __('حفظ') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >{{ __('تم الحفظ بنجاح.') }}</p>
            @endif
        </div>
    </form>
</section>
