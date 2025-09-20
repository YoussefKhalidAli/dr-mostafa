<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-600">
            {{ __('حذف الحساب') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('بمجرد حذف الحساب سيتم حذف جميع بياناتك بشكل نهائي. يرجى تحميل أي بيانات مهمة قبل المتابعة.') }}
        </p>
    </header>

    <x-danger-button
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('حذف الحساب') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('هل أنت متأكد أنك تريد حذف حسابك؟') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('بمجرد الحذف سيتم مسح جميع بياناتك نهائياً. يرجى إدخال كلمة المرور للتأكيد.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('كلمة المرور') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 border-gray-300 rounded-lg"
                    placeholder="{{ __('كلمة المرور') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('إلغاء') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    {{ __('حذف الحساب نهائياً') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
