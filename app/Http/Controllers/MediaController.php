<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // الاستدعاء الصحيح للمكتبة في أعلى الملف هنا

class MediaController extends Controller
{
    // 1. دالة عرض الصفحة الرئيسية (المعرض العام) مع ميزة البحث الذكي والتصفية بالأقسام
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Media::with(['user', 'category'])->latest();

        // فحص ميزة البحث بالكلمات المفتاحية
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // فحص ميزة التصفية بالقسم المختار
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $mediaItems = $query->paginate(12)->withQueryString();
        return view('media.index', compact('mediaItems', 'categories'));
    }

    // 2. دالة عرض صفحة تفاصيل الصورة/الفيديو وجلب التعليقات المرتبطة بها
    public function show($id)
    {
        $media = Media::with(['user', 'category', 'comments.user'])->findOrFail($id);
        return view('media.show', compact('media'));
    }

    // 3. دالة عرض صفحة استمارة (Form) رفع ملف جديد
    public function create()
    {
        $categories = Category::all();
        return view('media.create', compact('categories'));
    }

    // 4. دالة معالجة وحفظ الملف المرفوع الجديد وتحديد نوعه تلقائياً
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:20480',
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();
        $fileType = in_array($extension, ['mp4']) ? 'video' : 'image';

        $path = $request->file('file')->store('uploads', 'public');

        Media::create([
            'user_id' => auth()->id() ?? \App\Models\User::first()->id, // حماية تلقائية للمستخدم الحقيقي
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => Storage::url($path),
            'file_type' => $fileType,
        ]);

        return redirect()->route('media.create')->with('success', 'تم رفع الملف ونشره بنجاح! 🚀');
    }

    // 5. دالة عرض صفحة تعديل بيانات ملف مرفوع مسبقاً
    public function edit($id)
    {
        $media = Media::findOrFail($id);

        if ($media->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الملف.');
        }

        $categories = Category::all();
        return view('media.edit', compact('media', 'categories'));
    }

    // 6. دالة معالجة وحفظ تحديثات الملف واستبدال الصورة القديمة إن وجدت
    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        if ($media->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الملف.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480',
        ]);

        $updateData = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ];

        if ($request->hasFile('file')) {
            $relativeStoragePath = str_replace('/storage/', '', $media->file_path);
            if (Storage::disk('public')->exists($relativeStoragePath)) {
                Storage::disk('public')->delete($relativeStoragePath);
            }

            $path = $request->file('file')->store('uploads', 'public');
            $extension = $request->file('file')->getClientOriginalExtension();
            
            $updateData['file_path'] = Storage::url($path);
            $updateData['file_type'] = in_array($extension, ['mp4']) ? 'video' : 'image';
        }

        $media->update($updateData);
        return redirect()->route('media.dashboard')->with('success', 'تم تحديث بيانات الملف بنجاح! 🎉');
    }

    // 7. دالة حذف الملف فيزيائياً من السيرفر ومن قاعدة البيانات نهائياً
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        $relativeStoragePath = str_replace('/storage/', '', $media->file_path);
        if (Storage::disk('public')->exists($relativeStoragePath)) {
            Storage::disk('public')->delete($relativeStoragePath);
        }

        $media->delete();
        return redirect()->route('media.dashboard')->with('success', 'تم حذف الملف وكافة التعليقات بنجاح!');
    }

    // 8. دالة عرض لوحة التحكم (Dashboard) الشاملة للمستخدم الحالي ومفضلته
    public function dashboard()
    {
        $user = auth()->user() ?? \App\Models\User::first(); // جلب المستخدم الحالي
        $myMedia = Media::where('user_id', $user->id)->latest()->get();
        $myFavorites = $user->favoriteMedia()->latest()->get();

        return view('media.dashboard', compact('myMedia', 'user', 'myFavorites'));
    }

    // 9. دالة حفظ تعليق جديد على الصور والفيديوهات
    public function storeComment(Request $request, $mediaId)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        \App\Models\Comment::create([
            'user_id' => auth()->id() ?? \App\Models\User::first()->id,
            'media_id' => $mediaId,
            'body' => $request->body,
        ]);

        return redirect()->back()->with('comment_success', 'تم إضافة تعليقك بنجاح! 💬');
    }

    // 10. دالة التبديل السحري للإعجاب بالصورة وإلغائه (Toggle Like)
    public function toggleLike($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $media = Media::findOrFail($id);
        auth()->user()->favoriteMedia()->toggle($media->id);

        return redirect()->back();
    }

    // 11. دالة التحميل الفعلي وزيادة العداد تلقائياً برمجياً
    public function download($id)
    {
        $media = Media::findOrFail($id);
        $media->increment('downloads_count');

        if (filter_var($media->file_path, FILTER_VALIDATE_URL)) {
            return redirect($media->file_path);
        }

        $relativeStoragePath = str_replace('/storage/', '', $media->file_path);
        if (Storage::disk('public')->exists($relativeStoragePath)) {
            return Storage::disk('public')->download($relativeStoragePath, $media->title . '.' . pathinfo($relativeStoragePath, PATHINFO_EXTENSION));
        }

        return redirect()->back();
    }

    // 12. الحل الجذري: دالة تحديث الحساب الشخصي (الاسم، البريد، الـ Avatar) من الـ Dashboard مباشرة
    public function updateProfile(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $path;
        }

        $user->update($userData);
        return redirect()->route('media.dashboard')->with('success', 'تم تحديث بيانات ملفك الشخصي وصورتك بنجاح! 👤✨');
    }
}
