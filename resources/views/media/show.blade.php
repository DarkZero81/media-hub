@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- القسم الأيمن: مشغل الوسائط والبيانات (مساحة 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- صندوق العرض الذكي العصري (يدعم الصور والفيديوهات بشكل ديناميكي) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden p-4 border border-slate-100 dark:border-slate-700/50 transition-colors duration-300">
                <div class="relative w-full aspect-[16/10] bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center rounded-xl overflow-hidden">
                    
                    @if($media->file_type === 'video')
                        <!-- مشغل فيديو عصري يدعم التحكم الكامل والتشغيل التلقائي الصامت -->
                        <video class="w-full h-full object-contain bg-black" controls preload="metadata" playsinline>
                            <source src="{{ $media->file_path }}" type="video/mp4">
                            عذراً، متصفحك لا يدعم مشغل الفيديو.
                        </video>
                    @else
                        <!-- عرض الصورة الافتراضي مع حماية الروابط المكسورة -->
                        <img src="{{ $media->file_path }}" 
                             alt="{{ $media->title }}" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='https://placehold.co';" />
                    @endif

                </div>
            </div>
            
            <!-- صندوق بيانات الصورة والوصف -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="space-y-3 flex-1">
                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full">
                            {{ $media->category->name }}
                        </span>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">{{ $media->title }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm flex items-center gap-2">
                            تم الرفع بواسطة 
                            <span class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                <img src="https://ui-avatars.com{{ urlencode($media->user->name) }}&background=4f46e5&color=fff&rounded=true&size=32" class="w-5 h-5 rounded-full inline-block shadow-sm">
                                {{ $media->user->name }}
                            </span> 
                            - {{ $media->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    <!-- أزرار التحكم الجانبية (تحميل + إعجاب) مرتبة بشكل متناسق للشاشات والهواتف -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <!-- زر الإعجاب والمفضلة المطور -->
                        <form action="{{ route('media.like', $media->id) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" 
                                class="w-full sm:w-auto px-5 py-3 rounded-xl font-semibold border transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 shadow-sm text-sm
                                {{ $media->isLikedBy(auth()->user()) 
                                    ? 'bg-red-500 hover:bg-red-600 text-white border-transparent' 
                                    : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                @if($media->isLikedBy(auth()->user()))
                                    ❤️ <span>مفضل</span>
                                @else
                                    🤍 <span>حفظ</span>
                                @endif
                                <span class="bg-slate-100/20 dark:bg-slate-900/40 px-1.5 py-0.5 rounded-md text-xs">
                                    {{ $media->likedByUsers()->count() }}
                                </span>
                            </button>
                        </form>

                        <!-- زر التحميل الحقيقي الفعال -->
                        <a href="{{ route('media.download', $media->id) }}" 
                           class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-medium shadow-sm transition flex items-center justify-center gap-2 text-sm shrink-0">
                            📥 تحميل
                        </a>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">الوصف:</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-sm">
                        {{ $media->description ?? 'لا يوجد وصف متاح لهذا الملف.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- القسم الأيسر: صندوق التعليقات العصري (مساحة 1/3) -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 h-fit space-y-6 transition-colors duration-300">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b pb-3 border-slate-100 dark:border-slate-700/50">
                التعليقات ({{ $media->comments->count() }})
            </h3>
            
            <!-- قائمة التعليقات الديناميكية المعززة بالهوية البصرية والـ Avatars -->
            <div class="space-y-4 max-h-[400px] overflow-y-auto pl-2">
                @forelse($media->comments as $comment)
                    <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700/30 flex gap-3 items-start transition-colors duration-300">
                        <!-- جلب الصورة الدائرية الاحترافية للكاتب عبر دالة الموديل -->
                        <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-9 h-9 rounded-full shadow-sm border border-slate-200/50 dark:border-slate-700/30 shrink-0">
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $comment->user->name }}</h4>
                                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 dark:text-slate-500 text-center py-6 text-sm">لا توجد تعليقات بعد، كن أول من يعلق!</p>
                @endforelse
            </div>

            <!-- نموذج كتابة تعليق جديد -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50">
                @if(session('comment_success'))
                    <div class="bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 p-3 rounded-xl mb-3 text-xs font-medium border border-green-100 dark:border-green-900/30">
                        {{ session('comment_success') }}
                    </div>
                @endif

                <form action="{{ route('comments.store', $media->id) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="3" required
                        class="w-full p-3 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-slate-400 dark:placeholder-slate-500" 
                        placeholder="اكتب تعليقك هنا..."></textarea>
                    
                    @error('body')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="w-full mt-2 bg-slate-950 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-700 text-white py-2.5 rounded-xl text-sm font-medium transition-colors duration-200">
                        إرسال التعليق 💬
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
