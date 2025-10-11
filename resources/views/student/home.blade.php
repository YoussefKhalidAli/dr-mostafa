@extends('layouts.student')

@section('content')
<!-- ترحيب الطالب -->
<div class="mb-8">
    <div class="bg-gradient-to-l from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-xl islamic-pattern">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    مرحباً بك، {{ Auth::user()->name ?? 'طالب' }}! 👋
                </h1>
                <p class="text-primary-200 text-lg">نتمنى لك يوماً دراسياً مثمراً ومفيداً</p>
                <div class="flex items-center mt-3 text-primary-100">
                    <i class="fas fa-calendar ml-2"></i>
                    <span>{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l، j F Y') }}</span>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-3xl text-accent-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 md:p-6 text-white shadow-lg transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base text-blue-100 mb-1">مجموعاتي</h3>
                <p class="text-2xl md:text-3xl font-bold">{{ $approvedGroupsCount }}</p>
                <p class="text-xs text-blue-200 mt-1">نشطة</p>
            </div>
            <i class="fas fa-users text-2xl md:text-3xl text-blue-200"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 md:p-6 text-white shadow-lg transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base text-green-100 mb-1">الجلسات</h3>
                <p class="text-2xl md:text-3xl font-bold">{{ $weeklySessionsCount }}</p>
                <p class="text-xs text-green-200 mt-1">هذا الأسبوع</p>
            </div>
            <i class="fas fa-video text-2xl md:text-3xl text-green-200"></i>
        </div>
    </div>
</div>

<!-- الجلسات القادمة -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2 md:mb-0 flex items-center">
            <i class="fas fa-clock ml-2 text-primary-500"></i>
            الجلسات القادمة
        </h2>
        <a href="{{ route('student.sessions') }}" class="text-primary-600 hover:text-primary-800 font-medium text-sm flex items-center">
            عرض جميع الجلسات
            <i class="fas fa-arrow-left mr-2"></i>
        </a>
    </div>

    @if($liveSession)
    <!-- جلسة مباشرة الآن -->
    <div class="bg-gradient-to-l from-red-50 to-red-100 border-2 border-red-200 rounded-xl p-4 md:p-6 shadow-lg mb-4">
        <div class="flex items-center mb-3">
            <div class="w-3 h-3 bg-red-500 rounded-full ml-2 animate-pulse"></div>
            <span class="text-red-600 font-bold text-sm bg-red-200 px-3 py-1 rounded-full">مباشر الآن</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-red-800 mb-2">{{ $liveSession->title ?? $liveSession->group->name }}</h3>
                <div class="flex items-center text-sm text-red-600 mb-2">
                    <i class="fas fa-user ml-1"></i>
                    <span class="ml-4">المعلم: {{ $liveSession->group->teacher->name ?? 'غير محدد' }}</span>
                    <i class="fas fa-users ml-1"></i>
                    <span>{{ $liveSession->group->members_count }} طالب</span>
                </div>
                {{-- <div class="text-sm text-red-500">
                    <i class="fas fa-clock ml-1"></i>
                    بدأت منذ {{ $liveSession->time->diffForHumans() }}
                </div> --}}
            </div>
            <a href="{{ $liveSession->join_link ?? '#' }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition shadow-lg w-full md:w-auto text-center">
                <i class="fas fa-video ml-2"></i>
                انضم الآن
            </a>
        </div>
    </div>
    @endif

    <!-- الجلسات القادمة -->
    @if($upcomingSessions->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        @foreach($upcomingSessions->take(2) as $session)
        <div class="bg-white border border-blue-200 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-100 px-3 py-1 rounded-lg">
                    <span class="text-blue-600 font-semibold text-sm">{{ $session->time->format('l - H:i') }}</span>
                </div>
                <i class="fas fa-bookmark text-gray-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $session->title ?? $session->group->name }}</h3>
            <p class="text-gray-600 mb-3">المعلم: {{ $session->group->teacher->name ?? 'غير محدد' }}</p>
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <i class="fas fa-clock ml-1 text-blue-500"></i>
                <span class="ml-3">{{ $session->duration ?? 60 }} دقيقة</span>
                <i class="fas fa-users ml-1 text-blue-500"></i>
                <span>{{ $session->group->name }}</span>
            </div>
            {{-- <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                <i class="fas fa-bell ml-1"></i>
                تذكيرني
            </button> --}}
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-calendar-times text-4xl mb-4"></i>
        <p>لا توجد جلسات مجدولة حالياً</p>
    </div>
    @endif
</div>

<!-- مجموعاتي -->
@if($joinedGroups->count() > 0)
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2 md:mb-0 flex items-center">
            <i class="fas fa-users ml-2 text-primary-500"></i>
            مجموعاتي
        </h2>
        <a href="{{ route('student.groups') }}" class="text-primary-600 hover:text-primary-800 font-medium text-sm flex items-center">
            عرض جميع المجموعات
            <i class="fas fa-arrow-left mr-2"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @php
        $colors = ['purple', 'green', 'blue', 'orange', 'pink', 'indigo'];
        @endphp
        @foreach($joinedGroups as $index => $group)
        @php
        $color = $colors[$index % count($colors)];
        @endphp
        <div class="bg-gradient-to-br from-{{ $color }}-500 to-{{ $color }}-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium">نشط</span>
            </div>
            <h3 class="text-lg font-bold mb-2">{{ $group->title }}</h3>
            <p class="text-{{ $color }}-200 text-sm mb-3">{{ $group->description ?? 'لا يوجد وصف' }}</p>
            <div class="flex items-center text-sm text-{{ $color }}-100 mb-4">
                <i class="fas fa-user-tie ml-1"></i>
                <span class="ml-3">{{ $group->teacher->name ?? 'غير محدد' }}</span>
                <i class="fas fa-users ml-1"></i>
                <span>{{ $group->members_count }} طالب</span>
            </div>
            {{-- <button class="w-full bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition text-sm backdrop-blur-sm">
                <i class="fas fa-eye ml-1"></i>
                عرض التفاصيل
            </button> --}}
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- جميع المجموعات المتاحة -->
@if($availableGroups->count() > 0)
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2 md:mb-0 flex items-center">
            <i class="fas fa-globe ml-2 text-primary-500"></i>
            مجموعات متاحة للانضمام
        </h2>
        {{-- <div class="flex gap-2">
            <select class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option>جميع المراحل</option>
                <option>الصف الرابع</option>
                <option>الصف الخامس</option>
                <option>الصف السادس</option>
            </select>
            <select class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option>جميع المواد</option>
                <option>الرياضيات</option>
                <option>العلوم</option>
                <option>اللغة العربية</option>
            </select>
        </div> --}}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($availableGroups as $group)
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-xl text-orange-600"></i>
                </div>
                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-medium">متاحة</span>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $group->name }}</h3>
            <p class="text-gray-600 text-sm mb-3">{{ $group->description ?? 'لا يوجد وصف' }}</p>
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <i class="fas fa-user-tie ml-1"></i>
                <span class="ml-3">{{ $group->teacher->name ?? 'غير محدد' }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <i class="fas fa-users ml-1"></i>
                <span class="ml-3">{{ $group->members_count }}/{{ $group->max_students ?? '∞' }} طالب</span>
            </div>
            <form method="POST" action="{{ route('student.groups.join', $group->id) }}">
                @csrf
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-plus ml-1"></i>
                    طلب الانضمام
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- رابط المزيد -->
    <div class="text-center mt-6">
        <a href="{{ route('student.groups') }}" class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition shadow-lg">
            <i class="fas fa-search ml-2"></i>
            استكشف المزيد من المجموعات
        </a>
    </div>
</div>
@endif

<!-- روابط سريعة -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-rocket ml-2 text-primary-500"></i>
        روابط سريعة
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
        <a href="{{ route('student.sessions') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition text-center">
            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mb-2">
                <i class="fas fa-video text-white"></i>
            </div>
            <span class="text-sm font-medium text-blue-800">جلساتي</span>
        </a>
        
        <a href="{{ route('student.groups') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition text-center">
            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-2">
                <i class="fas fa-users text-white"></i>
            </div>
            <span class="text-sm font-medium text-purple-800">مجموعاتي</span>
        </a>
    </div>
</div>

@endsection