<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
            <i class="fas fa-comments ml-2"></i>
            المحادثة مع الطالب: {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-6">
            <!-- العنوان -->
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-1">محادثة مع {{ $student->name }}</h1>
                    <p class="opacity-90 text-sm">يمكنك إرسال واستقبال الرسائل أدناه</p>
                </div>
                <a href="{{ route('teacher.chat.index') }}" class="bg-white text-sky-600 px-4 py-2 rounded-xl hover:bg-sky-100 transition text-sm font-medium">
                    <i class="fas fa-arrow-right ml-1"></i> العودة
                </a>
            </div>

            <!-- الرسائل -->
            <div id="chat-box"
                 class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6 mb-6 h-[65vh] overflow-y-auto scroll-smooth">
                <div id="messages-container">
                    @include('teacher.chat.partials.messages', ['messages' => $messages])
                </div>
            </div>

            <!-- إرسال رسالة -->
            <form id="chat-form" action="{{ route('teacher.chat.store', $student->id) }}" method="POST"
                  class="flex items-center gap-2">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $student->id }}">
                <input type="text" id="message-input" name="message"
                       placeholder="اكتب رسالتك هنا..."
                       required
                       class="flex-1 border border-sky-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400 text-sm placeholder-gray-400">
                <button type="submit"
                        class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-3 rounded-xl text-sm font-medium transition flex items-center gap-1">
                    <i class="fas fa-paper-plane"></i> إرسال
                </button>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const chatBox = $('#chat-box');
    const messagesContainer = $('#messages-container');
    const fetchUrl = "{{ route('teacher.chat.show', $student->id) }}";
    const storeUrl = "{{ route('teacher.chat.store', $student->id) }}";

    // 📨 Fetch only partial (messages)
    function fetchMessages() {
        $.ajax({
            url: fetchUrl,
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                messagesContainer.html(data); // partial returned directly
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
                fetchMessages(); // instantly refresh after send
            },
            error: function (xhr) {
                alert('حدث خطأ أثناء الإرسال!');
            }
        });
    });

    // ⏱️ Auto-refresh every 3 seconds
    setInterval(fetchMessages, 3000);

    // Scroll to bottom initially
    chatBox.scrollTop(chatBox[0].scrollHeight);
</script>

</x-app-layout>
