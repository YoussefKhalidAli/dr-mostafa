<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">

    <!-- شريط علوي -->
    <div class="bg-blue-600 shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-white">الملف الشخصي</h1>
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded-lg shadow hover:bg-gray-100 transition">
                ⬅ رجوع
            </a>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto space-y-8">

            {{-- تحديث بيانات الملف الشخصي --}}
            <div class="p-6 bg-white shadow rounded-2xl border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">تحديث البيانات الشخصية</h2>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- تغيير كلمة المرور --}}
            <div class="p-6 bg-white shadow rounded-2xl border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">تغيير كلمة المرور</h2>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- حذف الحساب --}}
            {{-- <div class="p-6 bg-white shadow rounded-2xl border border-gray-200">
                <h2 class="text-lg font-semibold text-red-600 mb-4 border-b pb-2">حذف الحساب</h2>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> --}}

        </div>
    </div>

</body>
</html>
