@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- معلومات المستخدم الإحصائية العصرية (تدعم الوضعين) -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-8 rounded-2xl text-white shadow-sm">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full border border-white/20 shadow-sm inline-block">
            مرحباً بك في لوحة التحكم، {{ $user->name }} 👋
        </h1>
        <p class="text-indigo-100 text-sm mt-1">هنا يمكنك إدارة الملفات والصور التي قمت بنشرها على المنصة.</p>
        
        <div class="flex gap-6 mt-6">
            <div class="bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm">
                <span class="block text-2xl font-bold">{{ $myMedia->count() }}</span>
                <span class="text-xs text-indigo-200">إجمالي ملفاتك</span>
            </div>
            <div class="bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm">
                <span class="block text-2xl font-bold">{{ $myMedia->sum('downloads_count') }}</span>
                <span class="text-xs text-indigo-200">إجمالي التحميلات</span>
            </div>
        </div>
    </div>

    <!-- رسائل النجاح -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 p-4 rounded-xl font-medium border border-green-100 dark:border-green-900/30">
            {{ session('success') }}
        </div>
    @endif

    <!-- قسم تعديل بيانات الملف الشخصي والصورة الشخصية -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6 transition-colors duration-300">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400">
                👤
            </div>
            <div>
                <h2 class="font-bold text-lg text-slate-900 dark:text-white">تعديل الملف الشخصي</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">حدّث اسمك وبريدك الإلكتروني والصورة الشخصية من لوحة التحكم مباشرة.</p>
            </div>
        </div>

        <form action="{{ route('profile.custom.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="profile-name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">الاسم</label>
                    <input id="profile-name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="profile-email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">البريد الإلكتروني</label>
                    <input id="profile-email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="profile-avatar" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">الصورة الشخصية</label>
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-white dark:border-slate-700 shadow-sm">
                    <input id="profile-avatar" type="file" name="avatar" accept="image/*"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                @error('avatar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-sm transition">
                حفظ التغييرات
            </button>
        </form>
    </div>

    <!-- جدول عرض وإدارة الملفات (متوافق بالكامل مع الوضع الداكن والمظلم) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-slate-50 dark:border-slate-700/50 flex justify-between items-center">
            <h2 class="font-bold text-lg text-slate-900 dark:text-white">ملفاتي المرفوعة</h2>
        </div>

        @if($myMedia->isEmpty())
            <div class="p-12 text-center text-slate-400 dark:text-slate-500">
                <p class="mb-4 text-sm">لم تقم برفع أي ملفات بعد.</p>
                <a href="{{ route('media.create') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline text-sm">ارفع أول ملف لك الآن ←</a>
            </div>
        @else
            <!-- الحاوية overflow-x-auto تضمن مرونة وعمل الجدول على الهواتف دون تخريب التصميم -->
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm border-b border-slate-100 dark:border-slate-700/40">
                            <th class="p-4 font-semibold">الملف</th>
                            <th class="p-4 font-semibold">التصنيف</th>
                            <th class="p-4 font-semibold">التحميلات</th>
                            <th class="p-4 font-semibold">تاريخ النشر</th>
                            <th class="p-4 font-semibold text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700/40 text-sm text-slate-700 dark:text-slate-300">
                        @foreach($myMedia as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20 transition-colors">
                                <td class="p-4 flex items-center gap-3">
                                    <img src="{{ $item->file_path }}" class="w-12 h-12 object-cover rounded-lg bg-slate-100 dark:bg-slate-900">
                                    <span class="font-medium text-slate-900 dark:text-white truncate max-w-[200px]">{{ $item->title }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2.5 py-1 rounded-md text-xs font-semibold">{{ $item->category->name }}</span>
                                </td>
                                <td class="p-4 font-semibold text-slate-900 dark:text-white">📥 {{ $item->downloads_count }}</td>
                                <td class="p-4 text-slate-400 dark:text-slate-500">{{ $item->created_at->format('Y/m/d') }}</td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <!-- زر العرض -->
                                        <a href="{{ route('media.show', $item->id) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition text-xs font-semibold">عرض</a>
                                        
                                        <!-- زر التعديل المطور المربوط بالمسار بدقة -->
                                        <a href="{{ route('media.edit', $item->id) }}" class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition text-xs font-semibold">تعديل</a>
                                        
                                        <!-- نموذج زر الحذف مع التأكيد -->
                                        <form action="{{ route('media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الملف نهائياً؟');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition text-xs font-semibold">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- قسم معرض الصور المفضلة المطور بالوضع المظلم المتناسق -->
    <div class="mt-12 space-y-6">
        <div class="flex items-center gap-2 border-b pb-3 border-slate-200 dark:border-slate-700/50">
            <span class="text-xl">❤️</span>
            <h2 class="font-extrabold text-xl text-slate-900 dark:text-white">الصور المحفوظة في مفضلتي</h2>
        </div>

        @if($myFavorites->isEmpty())
            <div class="p-10 text-center text-slate-400 dark:text-slate-500 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/40 transition-colors duration-300">
                <p class="text-sm">لم تقم بحفظ أي صور في مفضلتك بعد.</p>
                <a href="{{ route('media.index') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline text-xs mt-2 inline-block">تصفح المعرض الرئيسي واضغط ❤️ ←</a>
            </div>
        @else
            <!-- شبكة عرض مصغرة للمفضلة مرنة ومتناسقة على كافة الشاشات والهواتف -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($myFavorites as $fav)
                    <div class="group relative bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-100 dark:border-slate-700/50 aspect-square transition-all duration-300">
                        <img src="{{ $fav->file_path }}" alt="{{ $fav->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        
                        <!-- طبقة داكنة تظهر عند تمرير الماوس تحتوي على أزرار سريعة ومحمية الألوان لتبان فوق الصور دائماً -->
                        <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-between p-3">
                            <span class="text-[10px] font-bold text-white bg-indigo-600 px-2 py-0.5 rounded-md self-start truncate max-w-full">
                                {{ $fav->category->name }}
                            </span>
                            
                            <div class="flex justify-between items-center gap-2">
                                <h4 class="text-xs font-bold text-white truncate flex-1">{{ $fav->title }}</h4>
                                <a href="{{ route('media.show', $fav->id) }}" class="text-[10px] bg-white text-slate-900 px-2.5 py-1 rounded-md font-bold hover:bg-slate-100 transition shrink-0">
                                    عرض
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
