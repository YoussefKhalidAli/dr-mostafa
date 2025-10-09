<!-- Fixed Responsive Main Layout (app.blade.php) -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'منصة الدكتور مصطفى طنطاوي') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs': '475px',
                    },
                    colors: {
                        sky: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        blue: {
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        purple: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
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
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .floating {
            animation: floating 4s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 1s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-card {
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Mobile Sidebar Styles - FIXED */
        #mobile-sidebar {
            transform: translateX(100%);
            position: fixed;
            top: 0;
            right: 0;
            width: 280px;
            max-width: 85vw;
            height: 100vh;
            height: 100dvh; /* Better mobile support */
            background: linear-gradient(to bottom, #0284c7, #1d4ed8);
            z-index: 1000;
            box-shadow: -4px 0 15px rgba(0,0,0,0.3);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        #mobile-sidebar.show {
            transform: translateX(0);
        }
        
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; 
            left: 0; 
            right: 0; 
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        #sidebar-overlay.show {
            display: block !important;
            opacity: 1 !important;
        }
        
        /* Desktop sidebar - IMPROVED */
        #desktop-sidebar {
            display: none;
        }
        
        @media (min-width: 768px) {
            #desktop-sidebar {
                display: block;
                width: 256px;
                flex-shrink: 0;
            }
            #mobile-sidebar {
                display: none;
            }
            #mobile-menu-button {
                display: none;
            }
        }
        
        /* Smooth scrolling for sidebar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) transparent;
        }
        
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
        }
        
        /* User dropdown improvements */
        .user-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.2s ease;
        }
        
        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }
        
        /* Mobile notifications improvements */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: #ef4444;
            color: white;
            font-size: 0.625rem;
            font-weight: 700;
            border-radius: 50%;
            min-width: 1rem;
            height: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }
        
        @media (min-width: 640px) {
            .notification-badge {
                top: -4px;
                right: -4px;
                min-width: 1.25rem;
                height: 1.25rem;
                font-size: 0.75rem;
            }
        }
        
        /* Touch improvements for mobile */
        @media (max-width: 767px) {
            .nav-button {
                min-height: 44px;
                min-width: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar-link {
                min-height: 48px;
                padding: 12px 16px;
                display: flex;
                align-items: center;
            }
        }
        
        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }
        
        /* Better focus states for accessibility */
        .focus-ring:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* Improved animations */
        .slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Content area adjustments */
        .main-content {
            width: 100%;
            min-width: 0; /* Prevent flex item from overflowing */
        }
        
        /* Better responsive text sizing */
        .responsive-text {
            font-size: clamp(0.875rem, 2.5vw, 1rem);
        }
        
        .responsive-heading {
            font-size: clamp(1rem, 4vw, 1.25rem);
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 overflow-x-hidden">
    <div class="min-h-screen flex">
        
        <!-- Desktop Sidebar -->
        <aside id="desktop-sidebar" class="bg-gradient-to-b from-sky-600 to-blue-700 text-white islamic-pattern">
            <div class="h-full overflow-y-auto sidebar-scroll">
                <!-- صورة المستخدم -->
                <div class="px-4 py-6 text-center border-b border-white/10">
                    <div class="mx-auto w-20 h-20 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center mb-3 floating">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold">د. مصطفى طنطاوي</h3>
                    <p class="text-sm text-white/80">أستاذ المواد الشرعية</p>
                </div>
                
                <!-- قائمة التنقل -->
                <nav class="py-4">
    <div class="px-4 py-2 text-xs font-semibold text-white/60 uppercase tracking-wide">القائمة الرئيسية</div>
    
    <a href="{{ route('dashboard') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('dashboard') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-home ml-3 w-5"></i>
        <span>لوحة التحكم</span>
    </a>

    <a href="{{ route('courses.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('courses.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-book ml-3 w-5"></i>
        <span>الدورات</span>
    </a>

    <a href="{{ route('lessons.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('lessons.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-book ml-3 w-5"></i>
        <span>الدروس</span>
    </a>

    <a href="{{ route('sessions.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('sessions.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-video ml-3 w-5"></i>
        <span>الحصص</span>
    </a>

    <a href="{{ route('teacher.groups.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('teacher.groups.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-users ml-3 w-5"></i>
        <span>المجموعات</span>
    </a>

    <a href="{{ route('exams.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('exams.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-file-alt ml-3 w-5"></i>
        <span>الاختبارات</span>
    </a>

    <a href="{{ route('assignments.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('assignments.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-tasks ml-3 w-5"></i>
        <span>الواجبات</span>
    </a>

    <!-- 📨 الرسائل -->
    <a href="{{ route('teacher.contact.index') }}" 
       class="flex items-center px-4 py-3 mt-1 transition-all duration-200 focus-ring {{ request()->routeIs('contact.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
        <i class="fas fa-envelope ml-3 w-5"></i>
        <span>الرسائل</span>
    </a>

    <div class="px-4 py-2 text-xs font-semibold text-white/60 mt-6 uppercase tracking-wide">الإعدادات</div>

    <a href="#" class="flex items-center px-4 py-3 mt-1 hover:bg-white/10 transition-all duration-200 focus-ring">
        <i class="fas fa-cog ml-3 w-5"></i>
        <span>الإعدادات</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 mt-1 hover:bg-white/10 transition-all duration-200 focus-ring">
        <i class="fas fa-question-circle ml-3 w-5"></i>
        <span>المساعدة</span>
    </a>

    <!-- تسجيل الخروج -->
    <form method="POST" action="{{ route('logout') }}" class="mt-6 border-t border-white/20 pt-4">
        @csrf
        <button type="submit" class="flex items-center w-full px-4 py-3 mt-1 hover:bg-white/10 transition-all duration-200 focus-ring text-right">
            <i class="fas fa-sign-out-alt ml-3 w-5"></i>
            <span>تسجيل الخروج</span>
        </button>
    </form>
</nav>

            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="text-white islamic-pattern">
            <div class="h-full flex flex-col">
                <!-- Close button for mobile -->
                <div class="flex justify-between items-center p-4 border-b border-white/20 flex-shrink-0">
                    <h3 class="text-lg font-semibold">القائمة</h3>
                    <button onclick="closeMobileSidebar()" class="nav-button text-white hover:text-yellow-300 transition-colors focus-ring rounded-lg">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- صورة المستخدم -->
                <div class="px-4 py-4 text-center border-b border-white/10 flex-shrink-0">
                    <div class="mx-auto w-16 h-16 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center mb-3">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                    <h3 class="text-base font-semibold">د. مصطفى طنطاوي</h3>
                    <p class="text-xs text-white/80">أستاذ المواد الشرعية</p>
                </div>
                
                <!-- قائمة التنقل -->
                <nav class="flex-1 overflow-y-auto sidebar-scroll py-2">
                    <div class="px-4 py-2 text-xs font-semibold text-white/60 uppercase tracking-wide">القائمة الرئيسية</div>
                    
                    <a href="{{ route('dashboard') }}" onclick="closeMobileSidebar()" 
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-home ml-3 w-5 flex-shrink-0"></i>
                        <span>لوحة التحكم</span>
                    </a>
                    
                    <a href="{{ route('courses.index') }}" onclick="closeMobileSidebar()"
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-book ml-3 w-5 flex-shrink-0"></i>
                        <span>الدورات</span>
                    </a>
                    
                    <a href="{{ route('lessons.index') }}" onclick="closeMobileSidebar()"
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('lessons.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-book ml-3 w-5 flex-shrink-0"></i>
                        <span>الدروس</span>
                    </a>
                    
                    <a href="{{ route('sessions.index') }}" onclick="closeMobileSidebar()" 
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('sessions.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-video ml-3 w-5 flex-shrink-0"></i>
                        <span>الحصص</span>
                    </a>
                    
                    <a href="{{ route('teacher.groups.index') }}" onclick="closeMobileSidebar()" 
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('teacher.groups.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-users ml-3 w-5 flex-shrink-0"></i>
                        <span>المجموعات</span>
                    </a>
                    
                    <a href="{{ route('exams.index') }}" onclick="closeMobileSidebar()" 
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('exams.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-file-alt ml-3 w-5 flex-shrink-0"></i>
                        <span>الاختبارات</span>
                    </a>
                    
                    <a href="{{ route('assignments.index') }}" onclick="closeMobileSidebar()" 
                       class="sidebar-link text-sm focus-ring transition-all duration-200 {{ request()->routeIs('assignments.*') ? 'bg-white/10 border-r-4 border-yellow-400' : 'hover:bg-white/10' }}">
                        <i class="fas fa-tasks ml-3 w-5 flex-shrink-0"></i>
                        <span>الواجبات</span>
                    </a>
                    
                    <div class="px-4 py-2 text-xs font-semibold text-white/60 mt-4 uppercase tracking-wide">الإعدادات</div>
                    
                    <a href="#" onclick="closeMobileSidebar()" 
                       class="sidebar-link hover:bg-white/10 transition-all duration-200 text-sm focus-ring">
                        <i class="fas fa-cog ml-3 w-5 flex-shrink-0"></i>
                        <span>الإعدادات</span>
                    </a>
                    
                    <a href="#" onclick="closeMobileSidebar()" 
                       class="sidebar-link hover:bg-white/10 transition-all duration-200 text-sm focus-ring">
                        <i class="fas fa-question-circle ml-3 w-5 flex-shrink-0"></i>
                        <span>المساعدة</span>
                    </a>
                </nav>
                
                <!-- تسجيل الخروج -->
                <div class="border-t border-white/20 p-4 flex-shrink-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="closeMobileSidebar()" 
                                class="sidebar-link w-full hover:bg-white/10 transition-all duration-200 text-sm focus-ring justify-start">
                            <i class="fas fa-sign-out-alt ml-3 w-5 flex-shrink-0"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 flex flex-col min-h-screen main-content">
            <!-- شريط التنقل العلوي -->
            <nav class="bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg relative z-30">
                <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
                    <div class="flex justify-between items-center h-14 sm:h-16">
                        
                        <!-- اليسار (زر القائمة + الشعار) -->
                        <div class="flex items-center min-w-0 flex-1">
                            <!-- زر القائمة للجوال -->
                            <button class="md:hidden nav-button text-xl sm:text-2xl ml-2 sm:ml-3 focus:outline-none hover:text-yellow-300 transition-colors focus-ring rounded-lg" 
                                    id="mobile-menu-button">
                                <i class="fas fa-bars"></i>
                            </button>

                            <!-- شعار -->
                            <a href="{{ url('/') }}" class="flex items-center space-x-2 space-x-reverse min-w-0">
                                <i class="fas fa-graduation-cap text-lg sm:text-2xl flex-shrink-0"></i>
                                <span class="responsive-heading font-bold truncate">
                                    <span class="hidden sm:inline">منصة الدكتور مصطفى طنطاوي</span>
                                    <span class="sm:hidden">المنصة</span>
                                </span>
                            </a>
                        </div>

                        <!-- اليمين (إشعارات + حساب المستخدم) -->
                        <div class="flex items-center space-x-2 sm:space-x-4 space-x-reverse flex-shrink-0">
                            
                            <!-- الإشعارات -->
                            {{-- <button class="relative nav-button hover:text-yellow-300 transition-colors focus:outline-none focus-ring rounded-lg">
                                <i class="fas fa-bell text-lg sm:text-xl"></i>
                                <span class="notification-badge">3</span>
                            </button> --}}

                            <!-- قائمة المستخدم للشاشات الكبيرة -->
                            <div class="hidden md:flex relative">
                                <button class="nav-button flex items-center hover:text-yellow-300 transition-colors focus:outline-none focus-ring rounded-lg" 
                                        id="user-menu-button">
                                    <i class="fas fa-user-circle text-xl ml-2"></i>
                                    <span class="responsive-text font-medium hidden lg:block">مرحباً، د. مصطفى</span>
                                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                </button>

                                <!-- قائمة منسدلة للشاشات الكبيرة -->
                                <div class="user-dropdown absolute right-0 top-full mt-2 w-48 bg-white text-gray-700 rounded-lg shadow-xl border border-gray-200" 
                                     id="user-dropdown">
                                    <div class="py-2">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 transition-colors">
                                            <i class="fas fa-user ml-2"></i>الملف الشخصي
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 transition-colors">
                                            <i class="fas fa-cog ml-2"></i>الإعدادات
                                        </a>
                                        <hr class="my-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-right px-4 py-2 text-sm hover:bg-gray-100 transition-colors text-red-600">
                                                <i class="fas fa-sign-out-alt ml-2"></i>تسجيل الخروج
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- زر المستخدم للجوال -->
                            <button class="md:hidden nav-button hover:text-yellow-300 transition-colors focus:outline-none focus-ring rounded-lg" 
                                    id="mobile-user-button">
                                <i class="fas fa-user-circle text-lg"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </nav>

            <!-- عنوان الصفحة -->
            @isset($header)
                <header class="bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg">
                    <div class="max-w-7xl mx-auto py-3 sm:py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- المحتوى الرئيسي -->
            <main class="flex-1 p-3 sm:p-6 fade-in">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- overlay للجوال -->
    <div id="sidebar-overlay"></div>

    <script>
        // Enhanced JavaScript for Better Mobile Experience
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            let isAnimating = false;
            
            // Mobile sidebar functions
            function openMobileSidebar() {
                if (isAnimating) return;
                isAnimating = true;
                
                if (mobileSidebar && overlay) {
                    mobileSidebar.classList.add('show');
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    
                    setTimeout(() => {
                        isAnimating = false;
                    }, 300);
                }
            }
            
            function closeMobileSidebar() {
                if (isAnimating) return;
                isAnimating = true;
                
                if (mobileSidebar && overlay) {
                    mobileSidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                    
                    setTimeout(() => {
                        isAnimating = false;
                    }, 300);
                }
            }
            
            // Global function for onclick handlers
            window.closeMobileSidebar = closeMobileSidebar;
            
            // Menu button click handler
            if (menuButton) {
                menuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isOpen = mobileSidebar && mobileSidebar.classList.contains('show');
                    if (isOpen) {
                        closeMobileSidebar();
                    } else {
                        openMobileSidebar();
                    }
                });
            }
            
            // Overlay click handler
            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }
            
            // User dropdown functionality
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userDropdown.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('show');
                    }
                });
            }
            
            // Close sidebar on window resize to desktop
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (window.innerWidth >= 768) {
                        closeMobileSidebar();
                    }
                }, 150);
            });
            
            // Prevent sidebar close when clicking inside sidebar
            if (mobileSidebar) {
                mobileSidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Better touch handling for iOS
            if (mobileSidebar) {
                mobileSidebar.addEventListener('touchmove', function(e) {
                    e.stopPropagation();
                }, { passive: true });
            }
            
            // Keyboard accessibility
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileSidebar();
                    if (userDropdown) {
                        userDropdown.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>
</html>