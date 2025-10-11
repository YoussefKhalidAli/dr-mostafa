@extends('layouts.student')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-comments ml-2 text-sky-500"></i>
        المحادثة مع المعلم
    </h1>

    <!-- منطقة الرسائل -->
    <div id="chat-box"
         class="bg-gradient-to-br from-sky-50 to-white border border-sky-100 rounded-2xl shadow-md p-6 mb-6 h-[65vh] overflow-y-auto scroll-smooth">
        <div id="messages-container">
            @include('student.chat.partials.messages', ['messages' => $messages])
        </div>
    </div>

    <!-- نموذج إرسال الرسالة -->
    <form id="chat-form" action="{{ route('student.chat.store') }}" method="POST" class="flex items-center gap-2">
        @csrf
        <input 
            type="text" 
            id="message-input"
            name="message" 
            placeholder="اكتب رسالتك هنا..." 
            required
            class="flex-1 border border-sky-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400 text-sm placeholder-gray-400"
        >
        <button 
            type="submit" 
            class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-3 rounded-xl text-sm font-medium transition flex items-center gap-1"
        >
            <i class="fas fa-paper-plane"></i> إرسال
        </button>
    </form>
</div>

<!-- ✅ Real-time update script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const chatBox = $('#chat-box');
    const messagesContainer = $('#messages-container');
    const fetchUrl = "{{ route('student.chat.index') }}";
    const storeUrl = "{{ route('student.chat.store') }}";

    // 📨 Fetch latest messages (partial)
    function fetchMessages() {
        $.ajax({
            url: fetchUrl,
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                messagesContainer.html(data);
                chatBox.scrollTop(chatBox[0].scrollHeight);
            },
            error: function (xhr) {
                console.error("Error fetching messages:", xhr.responseText);
            }
        });
    }

    // 💬 Send message via AJAX
    $('#chat-form').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: storeUrl,
            type: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#message-input').val('');
                fetchMessages(); // refresh right after send
            },
            error: function () {
                alert('حدث خطأ أثناء إرسال الرسالة!');
            }
        });
    });

    // ⏱️ Auto refresh every 3 seconds
    setInterval(fetchMessages, 3000);

    // Scroll to bottom initially
    chatBox.scrollTop(chatBox[0].scrollHeight);
</script>
@endsection
