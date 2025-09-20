<!-- شريط التنقل العلوي -->
<nav class="bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- اليسار (زر القائمة + الشعار) -->
            <div class="flex items-center">
                <!-- زر القائمة للجوال -->
                <button class="md:hidden text-2xl mr-4 focus:outline-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- شعار -->
                <a href="{{ url('/') }}" class="flex items-center space-x-2 space-x-reverse">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <span class="text-lg sm:text-xl font-bold">منصة الدكتور مصطفى طنطاوي</span>
                </a>
            </div>

            <!-- اليمين (إشعارات + حساب المستخدم) -->
            <div class="hidden md:flex items-center space-x-6 space-x-reverse">
                
                <!-- الإشعارات -->
                <button class="relative hover:text-yellow-300 transition focus:outline-none">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        3
                    </span>
                </button>

                <!-- قائمة المستخدم -->
                <div class="relative group">
                    <button class="flex items-center hover:text-yellow-300 transition focus:outline-none">
                        <i class="fas fa-user-circle text-xl ml-2"></i>
                        <span class="text-sm font-medium">مرحباً، د. مصطفى</span>
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>

                    <!-- قائمة منسدلة -->
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-700 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">الملف الشخصي</a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">الإعدادات</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-right px-4 py-2 text-sm hover:bg-gray-100">تسجيل الخروج</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</nav>
