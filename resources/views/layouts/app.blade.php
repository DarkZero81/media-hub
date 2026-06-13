<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Media Hub - منصة الوسائط العصرية</title>

        <!-- Fonts
        <link rel="preconnect" href="https://bunny.net">
        <link href="https://bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

        <!-- الأكواد الرسمية للمشروع والمحملة عبر خادم الـ Vite المتوافق مع Breeze -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // كود التحقق الفوري والسريع من الثيم المظلم المتوافق مع ملف الـ CSS الخاص بمشروعك
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased transition-colors duration-300">
        <div class="min-h-screen">
            
            <!-- شريط التنقل العلوي الذكي المستجيب -->
            <nav class="bg-white dark:bg-slate-800 sticky top-0 z-50 border-b border-slate-200/60 dark:border-slate-700/50 shadow-sm transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center relative">
                    
                    <!-- الشعار -->
                    <a href="{{ route('media.index') }}" class="text-2xl font-black bg-gradient-to-l from-indigo-600 to-violet-500 bg-clip-text text-transparent tracking-wide z-50">
                        MediaHub 📸
                    </a>
                    
                    <!-- زر الهامبرغر للهاتف -->
                    <button id="menu-toggle" type="button" class="sm:hidden p-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition z-50">
                        <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- أزرار التحكم والقائمة الذكية -->
                    <div id="nav-menu" class="hidden sm:flex flex-col sm:flex-row items-center gap-4 sm:gap-6 absolute sm:static top-full right-0 w-full sm:w-auto bg-white dark:bg-slate-800 sm:bg-transparent p-6 sm:p-0 border-b sm:border-0 border-slate-200 dark:border-slate-700 shadow-lg sm:shadow-none z-40 transition-all duration-300">
                        
                        <!-- زر التبديل الجذاب والأنيميتد المختر من قبلك -->
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input id="theme-toggle-checkbox" class="sr-only peer" type="checkbox" />
                            <div class="w-20 h-10 rounded-full bg-gradient-to-r from-yellow-300 to-orange-400 peer-checked:from-slate-700 peer-checked:to-indigo-900 transition-all duration-500 after:content-['☀️'] after:absolute after:top-1 after:left-1 after:bg-white dark:after:bg-slate-800 after:rounded-full after:h-8 after:w-8 after:flex after:items-center after:justify-center after:transition-all after:duration-500 peer-checked:after:translate-x-10 peer-checked:after:content-['🌙'] after:shadow-md after:text-lg"></div>
                        </label>

                        @auth
                            <div class="flex items-center gap-2">
    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">
    <span class="text-sm font-bold text-slate-700 dark:text-slate-200 hidden sm:inline">{{ auth()->user()->name }}</span>
</div>

                            <a href="{{ route('media.dashboard') }}" class="w-full sm:w-auto text-center py-2 sm:py-0 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition">لوحة التحكم</a>
                            <a href="{{ route('media.create') }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-md shadow-indigo-100 dark:shadow-none">رفع ملف</a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto text-center">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 transition py-2 sm:py-0">خروج</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-auto text-center py-2 sm:py-0 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition">دخول</a>
                            <a href="{{ route('register') }}" class="w-full sm:w-auto text-center bg-slate-900 dark:bg-slate-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">إنشاء حساب</a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- محتوى الصفحات الديناميكي -->
            <main class="max-w-7xl mx-auto px-6 pb-16 pt-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

        <!-- السكربت المباشر والنظيف بدون أي استدعاءات خارجية لتلافي أخطاء الـ Console -->
        <script>
            const themeCheckbox = document.getElementById('theme-toggle-checkbox');
            
            if (document.documentElement.classList.contains('dark')) {
                themeCheckbox.checked = true;
            } else {
                themeCheckbox.checked = false;
            }

            themeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            });

            // قائمة الهامبرغر
            const menuToggle = document.getElementById('menu-toggle');
            const navMenu = document.getElementById('nav-menu');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('hidden');
                    hamburgerIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                });
            }
        </script>
    </body>
</html>
