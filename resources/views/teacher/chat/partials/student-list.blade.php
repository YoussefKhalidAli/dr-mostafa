<div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
    @if($students->count() > 0)
       <ul class="divide-y divide-gray-100">
    @foreach($students as $student)
        <li>
            <a href="{{ route('teacher.chat.show', $student->id) }}"
               class="flex items-center justify-between p-5 hover:bg-sky-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-sky-100 text-sky-600 flex items-center justify-center rounded-full font-bold">
                        {{ mb_substr($student->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $student->name }}
                            @if($student->unread)
                                <span class="ml-2 text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">جديد</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500 truncate max-w-[180px]">
                            {{ $student->last_message }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $student->last_message_time }}
                        </p>
                    </div>
                </div>
                <i class="fas fa-chevron-left text-gray-400"></i>
            </a>
        </li>
    @endforeach
</ul>

    @else
        <div class="text-center py-16 text-gray-500">
            <i class="fas fa-inbox text-4xl mb-4"></i>
            <p>لا توجد محادثات حتى الآن</p>
        </div>
    @endif
</div>
