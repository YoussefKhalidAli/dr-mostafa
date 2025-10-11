<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            <i class="fas fa-comments ml-2"></i>
            محادثات الطلاب
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-6">
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white mb-6">
                <h1 class="text-2xl font-bold mb-2">قائمة المحادثات</h1>
                <p class="opacity-90">اختر طالبًا لعرض المحادثة الخاصة به</p>
            </div>

            <div id="student-list">
                @include('teacher.chat.partials.student-list', ['students' => $students])
            </div>
        </div>
    </div>

    <script>
        function refreshList() {
            fetch("{{ route('teacher.chat.index') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('student-list').innerHTML = html;
            });
        }

        setInterval(refreshList, 3000); // refresh every 3 seconds
    </script>
</x-app-layout>
