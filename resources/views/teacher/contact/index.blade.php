<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            <i class="fas fa-envelope-open-text ml-2"></i>
            رسائل التواصل
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($messages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($messages as $message)
                        <div class="bg-gradient-to-br from-sky-50 to-white border border-sky-100 rounded-2xl shadow-md p-6 flex flex-col justify-between hover:shadow-lg transition">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
                                    <i class="fas fa-user ml-2 text-sky-500"></i>
                                    {{ $message->name }}
                                </h3>

                                <p class="text-sm text-gray-600 mb-3">
                                    {{ Str::limit($message->content, 80) }}
                                </p>

                                <div class="text-xs text-gray-500 space-y-1">
                                    <p><i class="fas fa-phone ml-1 text-green-500"></i> {{ $message->phone ?? 'غير متوفر' }}</p>
                                    <p><i class="fas fa-heading ml-1 text-sky-400"></i> {{ $message->title }}</p>
                                    <p><i class="far fa-clock ml-1 text-yellow-500"></i> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <a href="{{ route('teacher.contact.show', $message->id) }}" 
                                   class="block w-full text-center py-2 px-4 bg-sky-500 text-white rounded-xl hover:bg-sky-600 transition text-sm font-medium">
                                    <i class="fas fa-eye ml-2"></i> عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4"></i>
                    <p>لا توجد رسائل حالياً</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
