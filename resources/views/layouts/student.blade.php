<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'لوحة الطالب') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        accent: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .sidebar { 
            transition: transform 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 25px 0 0 25px;
            margin: 2px 0;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-right: 4px solid #fbbf24;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .sidebar { display: block; }
        
        @media (max-width: 1024px) {
            .sidebar { width: 240px; }
        }
        
        @media (max-width: 768px) {
            .sidebar { 
                transform: translateX(100%); 
                position: fixed; 
                top: 0; 
                right: 0; 
                width: 280px; 
                height: 100vh; 
                background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
                z-index: 50; 
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.3);
            }
            .sidebar.active { transform: translateX(0); }
            .overlay { 
                display: none; 
                position: fixed; 
                top: 0; 
                left: 0; 
                right: 0; 
                bottom: 0; 
                background-color: rgba(0, 0, 0, 0.5); 
                z-index: 40; 
                backdrop-filter: blur(2px);
            }
            .overlay.active { display: block; }
            .nav-link { padding: 12px 16px; font-size: 16px; }
            .nav-link i { font-size: 18px; margin-left: 12px; }
        }
        
        @media (max-width: 640px) {
            .sidebar { width: 100vw; }
            main { padding: 1rem; }
            nav .max-w-7xl { padding: 0 1rem; }
            nav h1, nav span { font-size: 16px; }
            .notification-badge { height: 18px; width: 18px; font-size: 10px; }
        }
        
        @media (max-width: 480px) {
            nav .flex.justify-between > div:last-child span { display: none; }
            .sidebar { width: 100vw; }
            main { padding: 0.75rem; }
        }
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>

    {{-- سكشن للستايلات الإضافية --}}
    @yield('styles')
</head>
<body class="antialiased bg-primary-50 text-gray-800 overflow-x-hidden">
    <div class="min-h-screen flex">
        <!-- الشريط الجانبي -->
        <aside class="sidebar bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white w-64 lg:w-64 md:w-56 flex-shrink-0 islamic-pattern block" id="sidebar">
            <div class="h-full overflow-hidden py-4">
                <!-- صورة الطالب -->
                <div class="px-4 py-6 text-center">
                    <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-white/20 to-white/10 flex items-center justify-center mb-3 shadow-lg">
                        <i class="fas fa-user-graduate text-2xl text-accent-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">{{ Auth::user()->name ?? 'طالب' }}</h3>
                    <p class="text-sm text-primary-200">لوحة الطالب</p>
                </div>
                
                <!-- قائمة التنقل -->
<nav class="mt-6">
    <div class="px-4 py-2 text-xs font-semibold text-primary-300">القائمة الرئيسية</div>
    
    <a href="{{ route('student.home') }}" 
       class="nav-link flex items-center px-4 py-3 mx-2 {{ request()->routeIs('student.home') ? 'active' : '' }}">
        <i class="fas fa-home ml-3 text-accent-400"></i>
        <span class="font-medium">الرئيسية</span>
    </a>
    
    <a href="{{ route('student.groups') }}" 
       class="nav-link flex items-center px-4 py-3 mx-2 {{ request()->routeIs('student.groups') ? 'active' : '' }}">
        <i class="fas fa-users ml-3 text-primary-300"></i>
        <span class="font-medium">المجموعات</span>
    </a>

    <a href="{{ route('student.sessions') }}" 
       class="nav-link flex items-center px-4 py-3 mx-2 {{ request()->routeIs('student.sessions') ? 'active' : '' }}">
        <i class="fas fa-video ml-3 text-primary-300"></i>
        <span class="font-medium">الجلسات</span>
    </a>

    <a href="{{ route('student.exams.index') }}" 
       class="nav-link flex items-center px-4 py-3 mx-2 {{ request()->routeIs('student.exams.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt ml-3 text-primary-300"></i>
        <span class="font-medium">الامتحانات</span>
    </a>

    <a href="{{ route('student.assignments.index') }}" 
       class="nav-link flex items-center px-4 py-3 mx-2 {{ request()->routeIs('student.assignments.*') ? 'active' : '' }}">
        <i class="fas fa-tasks ml-3 text-primary-300"></i>
        <span class="font-medium">الواجبات</span>
    </a>
    
    <div class="px-4 py-2 text-xs font-semibold text-primary-300 mt-6 border-t border-white/20 pt-6">الإعدادات</div>
    
    <a href="#" class="nav-link flex items-center px-4 py-3 mx-2">
        <i class="fas fa-cog ml-3 text-primary-300"></i>
        <span class="font-medium">الإعدادات</span>
    </a>
    
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="nav-link flex items-center w-full px-4 py-3 mx-2 text-red-300 hover:text-red-200 hover:bg-red-500/20">
            <i class="fas fa-sign-out-alt ml-3"></i>
            <span class="font-medium">تسجيل الخروج</span>
        </button>
    </form>
</nav>



            </div>
        </aside>

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 overflow-hidden">
            <!-- شريط علوي -->
            <nav class="bg-gradient-to-l from-primary-500 to-primary-600 text-white shadow-xl sticky top-0 z-30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <!-- زر القائمة للجوال -->
                        <button class="md:hidden text-2xl mr-4 hover:bg-white/20 p-2 rounded-lg transition" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <!-- العنوان -->
                        <div class="flex items-center">
                            <i class="fas fa-graduation-cap text-accent-400 ml-2 text-xl"></i>
                            <span class="font-bold text-lg">لوحة الطالب</span>
                        </div>

                        <!-- يمين -->
                        <div class="flex items-center space-x-2 sm:space-x-4 space-x-reverse">
                            {{-- <button class="relative hover:bg-white/20 p-2 rounded-lg transition">
                                <i class="fas fa-bell text-lg sm:text-xl"></i>
                                <span class="notification-badge absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center font-bold shadow-lg">3</span>
                            </button> --}}
                            <div class="flex items-center">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-white/20 flex items-center justify-center ml-2">
                                   <a href="{{ route('profile.edit') }}"><i class="fas fa-user text-xs sm:text-sm text-accent-400"></i></a> 
                                </div>
                                <span class="font-medium text-sm sm:text-base hidden sm:inline">مرحباً، {{ Auth::user()->name ?? 'طالب' }}</span>
                                <span class="font-medium text-sm sm:hidden">{{ Auth::user()->name ?? 'طالب' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- محتوى الصفحة -->
            <main class="p-3 sm:p-4 lg:p-6 bg-gradient-to-br from-primary-50 to-primary-100 min-h-screen">
                <div class="max-w-7xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- overlay للجوال -->
    <div id="overlay" class="overlay"></div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // إضافة تأثير hover للروابط
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateX(5px)';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateX(0)';
                }
            });
        });
    </script>

    {{-- سكشن للسكربتات الإضافية --}}
    @yield('scripts')
</body>
</html>
