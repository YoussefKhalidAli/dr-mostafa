<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            <i class="fas fa-envelope ml-2"></i>
            تفاصيل الرسالة
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-sky-50 to-white border border-sky-100 rounded-2xl shadow-md p-6">
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-heading ml-2 text-sky-500"></i>
                        {{ $message->title }}
                    </h3>
                </div>

                <div class="space-y-2 text-gray-700">
                    <p><i class="fas fa-user ml-2 text-sky-400"></i> <strong>الاسم:</strong> {{ $message->name }}</p>
                    <p><i class="fas fa-phone ml-2 text-green-500"></i> <strong>رقم الهاتف:</strong> {{ $message->phone ?? 'غير متوفر' }}</p>
                    <p><i class="far fa-clock ml-2 text-yellow-500"></i> <strong>تاريخ الإرسال:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mt-5 bg-white border border-sky-100 rounded-xl p-4">
                    <p class="text-gray-800 leading-relaxed">
                        {{ $message->content }}
                    </p>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('teacher.contact.index') }}" 
                       class="py-2 px-4 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-right ml-2"></i> الرجوع
                    </a>

                    <form action="{{ route('teacher.contact.destroy', $message->id) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذه الرسالة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="py-2 px-4 bg-red-500 text-white rounded-xl hover:bg-red-600 transition text-sm font-medium">
                            <i class="fas fa-trash ml-2"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
