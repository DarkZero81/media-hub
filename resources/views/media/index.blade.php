@extends('layouts.app')

@section('content')


      <!-- شريط البحث العصري والمستجيب بالكامل للهواتف -->
    <div class="max-w-xl mx-auto mb-12 px-2">
        <form action="{{ route('media.index') }}" method="GET" class="relative flex items-center">
            <!-- الحفاظ على القسم المختار أثناء البحث إن وجد -->
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="relative w-full">
                <!-- أيقونة البحث الجانبية -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://w3.org">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- حقل الإدخال الذكي -->
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-24 pr-12 py-3.5 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-2xl shadow-sm border border-slate-200/60 dark:border-slate-700/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-300"
                    placeholder="ابحث عن الصور، العناوين، أو الكلمات المفتاحية..." />
                
                <!-- زر الحذف المباشر للبحث (يظهر فقط إذا كان هناك نص مكتوب) -->
                @if(request('search'))
                    <a href="{{ route('media.index', request()->except('search')) }}" class="absolute inset-y-0 left-20 flex items-center px-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition text-xs">
                        ✖ مسح
                    </a>
                @endif

                <!-- زر الإرسال العصري المثبت داخل الحقل -->
                <button type="submit" class="absolute left-2 top-2 bottom-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-xl text-xs font-bold shadow-sm transition-all duration-200 active:scale-95">
                    بحث
                </button>
            </div>
        </form>
    </div>


    <!-- شريط التصنيفات العصري التفاعلي -->
    <div class="flex items-center space-x-2 space-x-reverse overflow-x-auto pb-3 sm:pb-0 sm:flex-wrap gap-2 mb-10 bg-white dark:bg-slate-800 p-3 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/40 scrollbar-none transition-colors duration-300">
        <a href="{{ route('media.index') }}" 
           class="whitespace-nowrap px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ !request('category') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 dark:shadow-none' : 'bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
            الكل
        </a>
        @foreach($categories as $category)
            <a href="{{ route('media.index', ['category' => $category->slug]) }}" 
               class="whitespace-nowrap px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request('category') == $category->slug ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 dark:shadow-none' : 'bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- شبكة عرض الصور العصرية -->
        <!-- شبكة عرض الصور المحسنة بحالة الـ Empty State -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse($mediaItems as $item)
            <!-- كود كرت وعرض الصورة الافتراضي بالداخل (اتركه كما هو دون تغيير) -->
            <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-100 dark:border-slate-700/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <!-- ... محتوى الكرت الذي كتبناه سابقاً ... -->
                <div class="relative overflow-hidden aspect-[4/3] bg-slate-100 dark:bg-slate-900">
                    <a href="{{ route('media.show', $item->id) }}">
<div class="relative overflow-hidden aspect-[4/3] bg-slate-100 dark:bg-slate-900">
    <a href="{{ route('media.show', $item->id) }}">
        @if($item->file_type === 'video')
            <!-- مشغل فيديو صامت وخفيف كمعاينة داخل الكرت -->
            <video src="{{ $item->file_path }}" class="w-full h-full object-cover" muted loop playsinline onmouseover="this.play()" onmouseout="this.pause()"></video>
            <!-- أيقونة فيديو صغيرة في الزاوية -->
            <span class="absolute bottom-3 left-3 bg-slate-950/60 text-white p-1 rounded-md text-xs">🎥 فيديو</span>
        @else
            <img src="{{ $item->file_path }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
        @endif
    </a>
    <span class="absolute top-3 right-3 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/90 dark:bg-slate-900/90 backdrop-blur-sm px-2.5 py-1.5 rounded-lg shadow-sm">
        {{ $item->category->name }}
    </span>
</div>
                    </a>
                    <span class="absolute top-3 right-3 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/90 dark:bg-slate-900/90 backdrop-blur-sm px-2.5 py-1.5 rounded-lg shadow-sm">
                        {{ $item->category->name }}
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $item->title }}</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">بواسطة: <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $item->user->name }}</span></p>
                    </div>
                    <div class="flex justify-between items-center mt-5 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500 flex items-center gap-1">
                            📥 {{ $item->downloads_count }} تحميل
                        </span>
                        <a href="{{ route('media.show', $item->id) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-0.5 group/btn hover:underline">
                            عرض كامل ←
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <!-- صندوق الـ Empty State الفخم المخصص للوضعين -->
            <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 text-center py-20 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/40 p-8 space-y-4">
                <span class="text-5xl inline-block animate-bounce">🔍</span>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">عذراً، لم نجد أي نتائج تطابق بحثك!</h3>
                <p class="text-slate-400 dark:text-slate-500 text-sm max-w-sm mx-auto">تأكد من كتابة الكلمات بشكل صحيح أو جرب البحث عن مصطلحات عامة مثل "Nature" أو "Tech".</p>
                <a href="{{ route('media.index') }}" class="inline-block bg-indigo-65px text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-indigo-100 transition">
                    إعادة جلب كافة الصور
                </a>
            </div>
        @endforelse
    </div>


    <div class="mt-16 border-t border-slate-100 dark:border-slate-700/50 pt-8">
        {{ $mediaItems->links() }}
    </div>

    <!-- سكربت محلي مستقل داخل الصفحة لضمان عمل الزر 100% بدون تداخل الفئات -->
    <script>
        const localCheckbox = document.getElementById('page-theme-checkbox');
        
        // التحقق من الثيم وتأكيده للزر
        if (document.documentElement.classList.contains('dark')) {
            localCheckbox.checked = true;
        } else {
            localCheckbox.checked = false;
        }

        localCheckbox.addEventListener('change', function() {
            if (this.checked) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        });
    </script>
@endsection
