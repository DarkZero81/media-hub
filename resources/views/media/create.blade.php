@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">رفع ملف جديد (صورة / فيديو)</h1>

    <!-- عرض رسالة نجاح إذا تم الرفع -->
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- ملاحظة هامة: تم إضافة enctype="multipart/form-data" للسماح برفع الملفات -->
    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- حقل العنوان -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان الملف</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- حقل التصنيف -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">التصنيف</label>
            <select name="category_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">اختر تصنيفاً...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- حقل الوصف -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">الوصف (اختياري)</label>
            <textarea name="description" rows="4" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- حقل اختيار الملف -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">اختر ملف الصورة أو الفيديو</label>
            <input type="file" name="file" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            <p class="text-xs text-gray-400 mt-1">الصيغ المسموحة: png, jpg, jpeg, mp4 (الحد الأقصى 20 ميجابايت)</p>
            @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- زر الإرسال -->
        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-medium hover:bg-indigo-700 transition">
            نشر الملف الآن 🚀
        </button>
    </form>
</div>
@endsection
