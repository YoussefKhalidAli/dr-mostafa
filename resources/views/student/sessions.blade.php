@extends('layouts.student')

@section('content')
<!-- رأس الصفحة -->
<div class="mb-8">
    <div class="bg-gradient-to-l from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    <i class="fas fa-video ml-3 text-accent-400"></i>
                    جلساتي المباشرة
                </h1>
                <p class="text-primary-200 text-lg">شاهد وانضم إلى جلساتك التعليمية المباشرة</p>
            </div>
            <div class="hidden md:block">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-2xl text-accent-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
@php
    use Carbon\Carbon;

    // التوقيت الحالي UTC +3 (مصر)
    $now = \Carbon\Carbon::now();

    $liveSessions = $sessions->filter(function($session) use ($now) {
        $sessionTime = Carbon::parse($session->time);
        $endTime = $sessionTime->copy()->addHour();
        return $sessionTime <= $now && $endTime > $now;
    });

    $upcomingSessions = $sessions->filter(function($session) use ($now) {
        $sessionTime = Carbon::parse($session->time);
        return $sessionTime > $now;
    });

    $completedSessions = $sessions->filter(function($session) use ($now) {
        $sessionTime = Carbon::parse($session->time);
        $endTime = $sessionTime->copy()->addHour();
        return $endTime <= $now;
    });
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base text-green-100 mb-1">الجلسات المباشرة</h3>
                <p class="text-2xl md:text-3xl font-bold">{{ $liveSessions->count() }}</p>
            </div>
            <i class="fas fa-play-circle text-2xl md:text-3xl text-green-200"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base text-yellow-100 mb-1">جلسات قادمة</h3>
                <p class="text-2xl md:text-3xl font-bold">{{ $upcomingSessions->count() }}</p>
            </div>
            <i class="fas fa-clock text-2xl md:text-3xl text-yellow-200"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm md:text-base text-purple-100 mb-1">جلسات مكتملة</h3>
                <p class="text-2xl md:text-3xl font-bold">{{ $completedSessions->count() }}</p>
            </div>
            <i class="fas fa-check-circle text-2xl md:text-3xl text-purple-200"></i>
        </div>
    </div>
</div>

<!-- الجلسات المباشرة -->
@if($liveSessions->count() > 0)
<div class="mb-8">
    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-broadcast-tower ml-2 text-red-500"></i>
        الجلسات المباشرة الآن
        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full mr-2 animate-pulse">مباشر</span>
    </h2>

    <div class="space-y-4">
        @foreach($liveSessions as $session)
        @php
            $sessionTime = Carbon::parse($session->time);
            $endTime = $sessionTime->copy()->addHour();
            $remainingMinutes = $now->diffInMinutes($endTime, false);
            $elapsedMinutes = $sessionTime->diffInMinutes($now, false);
        @endphp
        <div class="bg-gradient-to-l from-red-50 to-red-100 border border-red-200 rounded-xl p-4 md:p-6 shadow-lg">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full ml-2 animate-pulse"></div>
                        <span class="text-red-600 font-semibold text-sm">مباشر الآن</span>
                        @if($remainingMinutes > 0)
                            <span class="text-red-500 text-xs mr-2">
                                (باقي {{ floor($remainingMinutes) }} دقيقة)
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-red-800 mb-2">{{ $session->title }}</h3>
                    <p class="text-red-600 mb-3">المعلم: {{ $session->group->teacher->name ?? 'غير محدد' }}</p>
                    <div class="flex items-center text-sm text-red-600">
                        <i class="fas fa-clock ml-1"></i>
                        @if($elapsedMinutes >= 60)
                            <span>بدأت منذ {{ round($elapsedMinutes / 60) }} ساعة</span>
                        @elseif($elapsedMinutes >= 1)
                            <span>بدأت منذ {{ floor($elapsedMinutes) }} دقيقة</span>
                        @else
                            <span>بدأت الآن</span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-2 w-full md:w-auto">
                    @if($session->link)
                    <a href="{{ $session->link }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition text-center shadow-lg">
                        <i class="fas fa-video ml-2"></i>
                        انضم الآن
                    </a>
                    @else
                    <button disabled class="bg-gray-400 text-white px-6 py-3 rounded-lg font-medium text-center shadow-lg cursor-not-allowed">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        رابط غير متوفر
                    </button>
                    @endif
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition text-center text-sm">
                        <i class="fas fa-info-circle ml-1"></i>
                        التفاصيل
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- الجلسات القادمة -->
@if($upcomingSessions->count() > 0)
<div class="mb-8">
    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-calendar-alt ml-2 text-blue-500"></i>
        الجلسات القادمة
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        @foreach($upcomingSessions as $session)
        @php
            $sessionTime = Carbon::parse($session->time);
            $minutesUntilSession = $now->diffInMinutes($sessionTime, false);
        @endphp
        <div class="bg-white border border-blue-200 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-100 px-3 py-1 rounded-lg">
                    <span class="text-blue-600 font-semibold text-sm">
                        {{ $session->time->translatedFormat('l - h:i A') }}
                    </span>
                </div>
                <div class="text-gray-400">
                    <i class="fas fa-bookmark"></i>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $session->title }}</h3>
            <p class="text-gray-600 mb-3">المعلم: {{ $session->group->teacher->name ?? 'غير محدد' }}</p>
            <div class="text-sm text-gray-500 mb-4">
                <div class="flex items-center mb-1">
                    <i class="fas fa-clock ml-1 text-blue-500"></i>
                    <span>المدة: 60 دقيقة</span>
                </div>
                <div class="flex items-center mb-1">
                    <i class="fas fa-users ml-1 text-blue-500"></i>
                    <span>المجموعة: {{ $session->group->title ?? 'غير محدد' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-hourglass-start ml-1 text-yellow-500"></i>
                    @if($minutesUntilSession > 0)
                        @if($minutesUntilSession < 60)
                            <span>باقي {{ $minutesUntilSession }} دقيقة</span>
                        @elseif($minutesUntilSession < 1440)
                            <span>باقي {{ round($minutesUntilSession / 60) }} ساعة</span>
                        @else
                            <span>باقي {{ round($minutesUntilSession / 1440) }} يوم</span>
                        @endif
                    @else
                        <span>بدأت بالفعل</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-2">
                <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-info-circle ml-1"></i>
                    التفاصيل
                </button>
                @if($minutesUntilSession <= 10 && $minutesUntilSession > 0)
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-bell ml-1"></i>
                    ستبدأ قريباً
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- الجلسات المكتملة -->
@if($completedSessions->count() > 0)
<div>
    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-history ml-2 text-gray-500"></i>
        الجلسات المكتملة
    </h2>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-right">
                        <th class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-700">اسم الجلسة</th>
                        <th class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-700">المعلم</th>
                        <th class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-700">التاريخ</th>
                        <th class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-700">المدة</th>
                        {{-- <th class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-700">الإجراءات</th> --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($completedSessions as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 md:px-6 py-4 text-sm font-medium text-gray-800">{{ $session->title }}</td>
                        <td class="px-4 md:px-6 py-4 text-sm text-gray-600">{{ $session->group->teacher->name ?? 'غير محدد' }}</td>
                        <td class="px-4 md:px-6 py-4 text-sm text-gray-600">
                            {{ $session->time->translatedFormat('d M Y - h:i A') }}
                        </td>
                        <td class="px-4 md:px-6 py-4 text-sm text-gray-600">60 دقيقة</td>
                        {{-- <td class="px-4 md:px-6 py-4 text-sm">
                            
                            <button class="text-primary-600 hover:text-primary-800 font-medium">
                                <i class="fas fa-play ml-1"></i>
                                مشاهدة التسجيل
                            </button>
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- رسالة في حالة عدم وجود جلسات -->
@if($sessions->count() == 0)
<div class="text-center py-12">
    <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-video text-3xl text-gray-400"></i>
    </div>
    <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد جلسات حالياً</h3>
    <p class="text-gray-500">لم يتم جدولة أي جلسات مباشرة لمجموعاتك بعد</p>
</div>
@endif
@endsection
