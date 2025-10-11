@if($messages->count() > 0)
    @foreach($messages as $message)
        <div class="mb-4 flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%] p-3 rounded-2xl shadow-sm 
                {{ $message->sender_id == auth()->id() 
                    ? 'bg-sky-500 text-white rounded-tr-none' 
                    : 'bg-gray-100 text-gray-800 rounded-tl-none' }}">
                <p class="text-sm leading-relaxed break-words">{{ $message->message }}</p>
                <span class="block text-xs mt-1 opacity-80">
                    {{ $message->created_at->format('H:i') }}
                </span>
            </div>
        </div>
    @endforeach
@else
    <div class="text-center text-gray-500 py-16">
        <i class="fas fa-comments text-4xl mb-4"></i>
        <p>ابدأ المحادثة مع معلمك الآن</p>
    </div>
@endif
