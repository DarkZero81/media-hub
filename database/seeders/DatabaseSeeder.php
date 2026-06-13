<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Media;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء تصنيفات ثابتة
        $categories = ['Nature', 'Technology', 'Architecture', 'Animals', 'Business'];
        $createdCategories = [];
        
        foreach ($categories as $categoryName) {
            $createdCategories[] = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }

        // 2. إنشاء مستخدم حقيقي ثابت لتسجيل الدخول به (استبدله ببياناتك المفضلة)
        $user = User::create([
            'name' => 'DarkZero',
            'email' => 'admin@mediahub.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // كلمة المرور الخاصة بك لدخول الموقع
            'remember_token' => Str::random(10),
        ]);

        // 3. روابط صورك الحقيقية الفخمة والحديثة التي اخترتها من Unsplash لضمان ضخها
        $urls = [
            'https://unsplash.com',
            'https://unsplash.com',
            'https://unsplash.com',
            'https://unsplash.com',
            'https://unsplash.com'
        ];

        // 4. حقن الصور الـ 5 وتوزيعها عشوائياً على التصنيفات السابقة
        $mediaItems = [];
        foreach ($urls as $index => $url) {
            $randomCategory = $createdCategories[array_rand($createdCategories)];
            
            $mediaItems[] = Media::create([
                'user_id' => $user->id,
                'category_id' => $randomCategory->id,
                'title' => 'الإبداع المرئي الرقمي - لوحة تجريبية رقم ' . ($index + 1),
                'description' => 'وصف تجريبي عصري مخصص لمنصة مشاركة الوسائط المتقدمة متوافق مع الوضع المظلم.',
                'file_path' => $url,
                'file_type' => 'image',
                'downloads_count' => rand(10, 90),
            ]);
        }

        // 5. حقن بعض التعليقات التجريبية الأساسية من المستخدم على صوره
        foreach ($mediaItems as $item) {
            Comment::create([
                'user_id' => $user->id,
                'media_id' => $item->id,
                'body' => 'هذه الصورة مذهلة جداً وتفاصيل الإضاءة والألوان فيها غاية في الاحترافية! 📸✨',
            ]);
        }
    }
}
