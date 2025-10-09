<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الدكتور مصطفى طنطاوي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap');
        
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            direction: rtl;
        }
        
        .fade-in {
            animation: fadeIn 2s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .floating {
            animation: floating 4s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }
        
        .wave {
            animation: wave 8s linear infinite;
        }
        
        @keyframes wave {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .course-card {
            transition: all 0.3s ease;
        }
        
        .course-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .flip-card {
            perspective: 1000px;
            height: 16rem;
        }
        
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        
        .flip-card-back {
            transform: rotateY(180deg);
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
        }
        
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .testimonial-card {
            opacity: 0;
            transition: opacity 0.5s ease;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .testimonial-card.active {
            opacity: 1;
        }
        
        .nav-dot {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .nav-dot.active {
            transform: scale(1.5);
            background-color: white;
        }
        
        /* تحسينات للشريط العلوي */
        .sticky-nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 50;
            background: rgba(14, 165, 233, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        /* تحسينات للهواتف */
        @media (max-width: 768px) {
            .flip-card {
                height: 14rem;
                margin-bottom: 1.5rem;
            }
            
            .mobile-menu {
                display: none;
            }
            
            .mobile-menu.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                right: 0;
                width: 100%;
                background: rgba(14, 165, 233, 0.95);
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-400 via-sky-500 to-blue-600 min-h-screen text-white overflow-x-hidden islamic-pattern pt-16">

    <!-- شريط التنقل -->
    <nav class="sticky-nav py-3 px-6 flex justify-between items-center">
        <div class="text-xl md:text-2xl font-bold flex items-center">
            <i class="fas fa-graduation-cap ml-2"></i>
            <span>د. مصطفى طنطاوي</span>
        </div>
        <div class="space-x-4 space-x-reverse hidden md:flex">
            <a href="#" class="hover:text-yellow-300 transition">الرئيسية</a>
            <a href="#" class="hover:text-yellow-300 transition">الدروس</a>
            <a href="#" class="hover:text-yellow-300 transition">المكتبة</a>
            <a href="#contact-section" class="hover:text-yellow-300 transition">اتصل بنا</a>
        </div>
        <button class="md:hidden text-xl" id="mobileMenuButton">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- قائمة الجوال -->
        <div class="mobile-menu md:hidden" id="mobileMenu">
            <a href="#" class="py-2 px-4 hover:bg-sky-600 rounded">الرئيسية</a>
            <a href="#" class="py-2 px-4 hover:bg-sky-600 rounded">الدروس</a>
            <a href="#" class="py-2 px-4 hover:bg-sky-600 rounded">المكتبة</a>
            <a href="#contact-section" class="py-2 px-4 hover:bg-sky-600 rounded">اتصل بنا</a>
        </div>
    </nav>

    <!-- المحتوى الرئيسي -->
    <main class="container mx-auto px-4 py-8 flex flex-col items-center">

        <!-- العنوان الرئيسي -->
        <div class="text-center fade-in mb-12 mt-8">
            <div class="inline-block mb-6 wave text-4xl">🌙</div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 drop-shadow-lg floating">
                مرحباً بكم مع د. مصطفى طنطاوي
            </h1>
            <p class="text-lg md:text-xl mb-8 opacity-90 max-w-2xl mx-auto leading-relaxed">
                أستاذ بجامعة الأزهر – أسيوط، متخصص في تدريس المواد الشرعية 
                لطلبة المعاهد والمدارس الأزهرية مثل <span class="font-semibold">الفقه</span> و<span class="font-semibold">الحديث</span> 
                وغيرها من العلوم الإسلامية النافعة.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-sky-600 font-semibold rounded-2xl shadow-lg hover:scale-105 transition transform duration-300 ease-in-out flex items-center justify-center">
                    <i class="fas fa-sign-in-alt ml-2"></i>
                    تسجيل الدخول
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3 bg-yellow-400 text-sky-900 font-semibold rounded-2xl shadow-lg hover:scale-105 transition transform duration-300 ease-in-out flex items-center justify-center">
                    <i class="fas fa-user-plus ml-2"></i>
                    إنشاء حساب
                </a>
            </div>
        </div>

        <!-- نبذة تعريفية -->
        <div class="fade-in bg-white/10 backdrop-blur-md rounded-3xl shadow-xl p-6 md:p-8 max-w-3xl text-center mx-4 mb-16">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">عن الدكتور</h2>
            <p class="text-lg leading-relaxed">
                الدكتور <span class="font-semibold">مصطفى طنطاوي</span> أحد أساتذة الأزهر المتميزين 
                بجامعة الأزهر – فرع أسيوط.  
                يسعى من خلال هذه المنصة إلى توفير تعليم شرعي أصيل لطلاب الأزهر 
                بطريقة عصرية ومبسطة، تشمل شرح الدروس، المتابعة مع الطلاب، 
                وإجراء اختبارات وواجبات إلكترونية لتثبيت الفهم.
            </p>
        </div>

        <!-- المواد الدراسية -->
        <section class="w-full max-w-6xl mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">المواد الدراسية</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- مادة 1 -->
                <div class="flip-card w-full mx-auto">
                    <div class="flip-card-inner">
                        <div class="flip-card-front course-card bg-white/10 backdrop-blur-md rounded-xl shadow-lg">
                            <div class="text-4xl mb-4">📖</div>
                            <h3 class="text-xl font-bold">علم الفقه</h3>
                            <p class="text-center mt-2">أصول الفقه وقواعده</p>
                        </div>
                        <div class="flip-card-back">
                            <h3 class="text-xl font-bold mb-4">علم الفقه</h3>
                            <p class="text-center">دراسة الأحكام الشرعية العملية المستفادة من أدلتها التفصيلية</p>
                            <a href="#" class="mt-6 px-4 py-2 bg-white text-sky-600 rounded-lg text-sm font-semibold">استعراض المحتوى</a>
                        </div>
                    </div>
                </div>
                
                <!-- مادة 2 -->
                <div class="flip-card w-full mx-auto">
                    <div class="flip-card-inner">
                        <div class="flip-card-front course-card bg-white/10 backdrop-blur-md rounded-xl shadow-lg">
                            <div class="text-4xl mb-4">🕌</div>
                            <h3 class="text-xl font-bold">علم الحديث</h3>
                            <p class="text-center mt-2">دراسة السنة النبوية</p>
                        </div>
                        <div class="flip-card-back">
                            <h3 class="text-xl font-bold mb-4">علم الحديث</h3>
                            <p class="text-center">دراسة أقوال النبي صلى الله عليه وسلم وأفعاله وتقريراته</p>
                            <a href="#" class="mt-6 px-4 py-2 bg-white text-sky-600 rounded-lg text-sm font-semibold">استعراض المحتوى</a>
                        </div>
                    </div>
                </div>
                
                <!-- مادة 3 -->
                <div class="flip-card w-full mx-auto">
                    <div class="flip-card-inner">
                        <div class="flip-card-front course-card bg-white/10 backdrop-blur-md rounded-xl shadow-lg">
                            <div class="text-4xl mb-4">✍️</div>
                            <h3 class="text-xl font-bold">علم التفسير</h3>
                            <p class="text-center mt-2">تفاسير القرآن الكريم</p>
                        </div>
                        <div class="flip-card-back">
                            <h3 class="text-xl font-bold mb-4">علم التفسير</h3>
                            <p class="text-center">بيان معاني القرآن الكريم واستخراج أحكامه وحكمه</p>
                            <a href="#" class="mt-6 px-4 py-2 bg-white text-sky-600 rounded-lg text-sm font-semibold">استعراض المحتوى</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- آراء الطلاب -->
        <section class="w-full max-w-4xl mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">آراء الطلاب</h2>
            <div class="relative h-80 overflow-hidden">
                <!-- testimonial cards -->
                <div class="testimonial-card active bg-white/10 backdrop-blur-md rounded-3xl p-6 flex flex-col items-center justify-center">
                    <div class="text-5xl mb-4">❝</div>
                    <p class="text-center text-lg mb-4">الدكتور مصطفى يشرح المادة بطريقة رائعة تجعلها سهلة الفهم وممتعة في نفس الوقت</p>
                    <div class="text-yellow-300">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="mt-4 font-semibold">- أحمد محمد، طالب في الصف الثاني الثانوي</p>
                </div>
                
                <div class="testimonial-card bg-white/10 backdrop-blur-md rounded-3xl p-6 flex flex-col items-center justify-center">
                    <div class="text-5xl mb-4">❝</div>
                    <p class="text-center text-lg mb-4">المنصة ساعدتني كثيراً في فهم مواد الفقه والحديث وتحسنت درجاتي بشكل ملحوظ</p>
                    <div class="text-yellow-300">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="mt-4 font-semibold">- فاطمة أحمد، طالبة في الصف الأول الثانوي</p>
                </div>
                
                <div class="testimonial-card bg-white/10 backdrop-blur-md rounded-3xl p-6 flex flex-col items-center justify-center">
                    <div class="text-5xl mb-4">❝</div>
                    <p class="text-center text-lg mb-4">الاختبارات الإلكترونية والواجبات ساعدتني على تثبيت المعلومات بشكل أفضل</p>
                    <div class="text-yellow-300">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="mt-4 font-semibold">- يوسف محمود، طالب في الصف الثالث الإعدادي</p>
                </div>
            </div>
            <div class="flex justify-center space-x-3 space-x-reverse mt-6">
                <button class="nav-dot w-3 h-3 bg-white/50 rounded-full active" data-index="0"></button>
                <button class="nav-dot w-3 h-3 bg-white/50 rounded-full" data-index="1"></button>
                <button class="nav-dot w-3 h-3 bg-white/50 rounded-full" data-index="2"></button>
            </div>
        </section>

        <!-- إحصائيات -->
        <section class="w-full max-w-4xl mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">إحصائيات المنصة</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <p>طالب مسجل</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2">120+</div>
                    <p>درس متاح</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <p>رضا الطلاب</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold mb-2">5</div>
                    <p>مواد دراسية</p>
                </div>
            </div>
        </section>

        <!-- CONTACT FORM -->
        <section id="contact-section" class="w-full max-w-4xl mb-20">
            <h2 class="text-3xl font-bold text-center mb-8">اتصل بنا</h2>

            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 md:p-10 shadow-lg w-full">
                <form id="contact-form" class="grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium">الاسم</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-3 rounded-lg bg-white/10 placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <p class="mt-1 text-xs text-red-300 hidden" id="error-name"></p>
                    </div>

                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium">رقم الهاتف (اختياري)</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full px-4 py-3 rounded-lg bg-white/10 placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <p class="mt-1 text-xs text-red-300 hidden" id="error-phone"></p>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block mb-2 text-sm font-medium">عنوان الرسالة</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-3 rounded-lg bg-white/10 placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <p class="mt-1 text-xs text-red-300 hidden" id="error-title"></p>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block mb-2 text-sm font-medium">محتوى الرسالة</label>
                        <textarea name="content" id="content" rows="6" required
                            class="w-full px-4 py-3 rounded-lg bg-white/10 placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/20"></textarea>
                        <p class="mt-1 text-xs text-red-300 hidden" id="error-content"></p>
                    </div>

                    <div class="col-span-1 md:col-span-2 flex items-center justify-center">
                        <button type="submit" id="contact-submit"
                            class="px-6 py-3 bg-yellow-400 text-sky-900 font-semibold rounded-2xl shadow-lg hover:scale-105 transition transform duration-200">
                            <i class="fas fa-paper-plane ml-2"></i> إرسال الرسالة
                        </button>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <div id="contact-success" class="hidden p-4 rounded-lg bg-green-600/80 text-white text-center"></div>
                        <div id="contact-fail" class="hidden p-4 rounded-lg bg-red-600/80 text-white text-center"></div>
                    </div>
                </form>
            </div>
        </section>

    </main>

    <!-- الفاصل الزخرفي -->
    <div class="w-full py-10 flex justify-center mb-10">
        <div class="h-1 w-24 bg-yellow-400 rounded-full"></div>
        <div class="h-1 w-24 bg-white mx-4 rounded-full"></div>
        <div class="h-1 w-24 bg-yellow-400 rounded-full"></div>
    </div>

    <!-- تذييل الصفحة -->
    <footer class="w-full py-8 bg-white/10 backdrop-blur-md">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0 text-center md:text-right">
                    <h3 class="text-2xl font-bold flex items-center justify-center md:justify-start">
                        <i class="fas fa-graduation-cap ml-2"></i>
                        د. مصطفى طنطاوي
                    </h3>
                    <p class="mt-2">تعليم شرعي أصيل لعصر رقمي</p>
                </div>
                <div class="flex space-x-4 space-x-reverse">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            <div class="border-t border-white/20 mt-8 pt-8 text-center">
                <p>© 2023 جميع الحقوق محفوظة | منصة الدكتور مصطفى طنطاوي</p>
            </div>
        </div>
    </footer>

    <!-- فقاعات متحركة في الخلفية -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse top-10 left-10"></div>
        <div class="absolute w-96 h-96 bg-sky-300/20 rounded-full blur-3xl animate-bounce bottom-10 right-10"></div>
        <div class="absolute w-64 h-64 bg-blue-400/30 rounded-full blur-2xl floating top-1/2 left-1/3"></div>
    </div>

    <script>
        // testimonial slider
        document.addEventListener('DOMContentLoaded', function() {
            const testimonials = document.querySelectorAll('.testimonial-card');
            const dots = document.querySelectorAll('.nav-dot');
            let currentIndex = 0;
            
            function showTestimonial(index) {
                testimonials.forEach(testimonial => testimonial.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                testimonials[index].classList.add('active');
                dots[index].classList.add('active');
                currentIndex = index;
            }
            
            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    showTestimonial(index);
                });
            });
            
            // Auto-rotate testimonials
            setInterval(() => {
                currentIndex = (currentIndex + 1) % testimonials.length;
                showTestimonial(currentIndex);
            }, 5000);
            
            // Mobile menu functionality
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('active');
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mobileMenu && mobileMenu.classList.contains('active') && 
                    !mobileMenu.contains(event.target) && 
                    !mobileMenuButton.contains(event.target)) {
                    mobileMenu.classList.remove('active');
                }
            });

            // Contact form handling
            const contactForm = document.getElementById('contact-form');
            const submitBtn = document.getElementById('contact-submit');
            const successBox = document.getElementById('contact-success');
            const failBox = document.getElementById('contact-fail');
            const errors = {
                name: document.getElementById('error-name'),
                phone: document.getElementById('error-phone'),
                title: document.getElementById('error-title'),
                content: document.getElementById('error-content'),
            };
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const postUrl = "{{ route('contact.store') }}";

            contactForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                // clear previous
                successBox.classList.add('hidden'); failBox.classList.add('hidden');
                Object.values(errors).forEach(n => { n.classList.add('hidden'); n.textContent = ''; });

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جارٍ الإرسال';

                const payload = {
                    name: document.getElementById('name').value.trim(),
                    phone: document.getElementById('phone').value.trim(),
                    title: document.getElementById('title').value.trim(),
                    content: document.getElementById('content').value.trim(),
                };

                try {
                    const res = await fetch(postUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (res.ok && data.success) {
                        successBox.textContent = data.message || 'تم إرسال رسالتك بنجاح.';
                        successBox.classList.remove('hidden');
                        // clear form
                        contactForm.reset();
                    } else if (res.status === 422) {
                        // validation errors
                        const json = data;
                        const v = json.errors || json;
                        if (v.name) {
                            errors.name.textContent = v.name[0] || v.name;
                            errors.name.classList.remove('hidden');
                        }
                        if (v.phone) {
                            errors.phone.textContent = v.phone[0] || v.phone;
                            errors.phone.classList.remove('hidden');
                        }
                        if (v.title) {
                            errors.title.textContent = v.title[0] || v.title;
                            errors.title.classList.remove('hidden');
                        }
                        if (v.content) {
                            errors.content.textContent = v.content[0] || v.content;
                            errors.content.classList.remove('hidden');
                        }
                        failBox.textContent = 'برجاء تصحيح الأخطاء الظاهرة أعلاه.';
                        failBox.classList.remove('hidden');
                    } else {
                        failBox.textContent = data.message || 'حدث خطأ أثناء الإرسال، حاول لاحقاً.';
                        failBox.classList.remove('hidden');
                    }
                } catch (err) {
                    console.error(err);
                    failBox.textContent = 'حدث خطأ في الاتصال. تأكد من اتصالك بالإنترنت.';
                    failBox.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane ml-2"></i> إرسال الرسالة';
                }
            });
        });
    </script>
</body>
</html>
