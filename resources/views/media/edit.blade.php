@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 transition-colors duration-300">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">تعديل بيانات الملف 📝</h1>

    <form action="{{ route('media.update', $media->id) }}" method="POST" class="space-y-6">
        @csrf

        <!-- حقل العنوان مع جلب العنوان الحالي -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">عنوان الملف</label>
            <input type="text" name="title" value="{{ old('title', $media->title) }}" 
                class="w-full p-3 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- حقل التصنيف مع تحديد التصنيف الحالي مسبقاً -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">التصنيف</label>
            <select name="category_id" class="w-full p-3 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $media->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- حقل الوصف مع جلب الوصف الحالي -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">الوصف</label>
            <textarea name="description" rows="4" class="w-full p-3 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm">{{ old('description', $media->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- معاينة مصغرة للملف الحالي المراد تعديله للجمالية البصرية -->
        <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50">
            <p class="text-xs text-slate-400 mb-2">معاينة الملف الحالي:</p>
            <img src="{{ $media->file_path }}" class="w-32 h-20 object-cover rounded-lg shadow-sm bg-slate-100">
        </div>

        <!-- أزرار التحكم -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium transition-all duration-200 active:scale-95 text-sm shadow-sm">
                حفظ التغييرات الجديدة 💾
            </button>
            <a href="{{ route('media.dashboard') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-medium hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm text-center">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
