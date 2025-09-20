<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('تغيير كلمة المرور') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('استخدم كلمة مرور قوية ومعقدة لضمان أمان حسابك.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('كلمة المرور الحالية')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded-lg" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('كلمة المرور الجديدة')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-lg" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-lg" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#2563eb] hover:bg-[#1e3a8a] text-white px-4 py-2 rounded-lg">
                {{ __('حفظ') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >{{ __('تم تغيير كلمة المرور.') }}</p>
            @endif
        </div>
    </form>
</section>
