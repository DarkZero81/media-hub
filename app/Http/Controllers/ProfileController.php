<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
public function update(ProfileUpdateRequest $request)
{
    $user = $request->user();

    // جلب البيانات التي تم التحقق منها تلقائياً من حزمة Breeze
    $user->fill($request->validated());

    // التحقق الفوري من البريد الإلكتروني إذا تم تعديله
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // المنطق الجديد: معالجة ورفع الصورة الشخصية الـ Avatar
    if ($request->hasFile('avatar')) {
        
        // التحقق يدويًا من شروط الصورة الشخصية
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // حد أقصى 2 ميجا
        ]);

        // حذف الصورة الشخصية القديمة من السيرفر لتوفير المساحة إن وجدت
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // رفع الصورة الجديدة في مجلق خاص يسمى avatars داخل الـ public storage
        $path = $request->file('avatar')->store('avatars', 'public');
        
        // حفظ المسار في حقل avatar
        $user->avatar = $path;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
