<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
public function run(): void
{
    // 1. إنشاء تصنيفات ثابتة ومحددة أولاً
    $categories = ['Nature', 'Technology', 'Architecture', 'Animals', 'Business'];
    
    foreach ($categories as $categoryName) {
        \App\Models\Category::create([
            'name' => $categoryName,
            'slug' => \Illuminate\Support\Str::slug($categoryName),
        ]);
    }

    // 2. إنشاء 10 مستخدمين وهميين
    $users = \App\Models\User::factory(10)->create();

    // 3. إنشاء 30 ملف وسائط (صور) وتوزيعها عشوائياً على المستخدمين والأقسام
    $mediaItems = \App\Models\Media::factory(30)->create([
        'user_id' => function () use ($users) {
            return $users->random()->id;
        },
        'category_id' => function () {
            return \App\Models\Category::inRandomOrder()->first()->id;
        }
    ]);

    // 4. إنشاء 50 تعليق عشوائي وتوزيعها على الصور والمستخدمين
    \App\Models\Comment::factory(50)->create([
        'user_id' => function () use ($users) {
            return $users->random()->id;
        },
        'media_id' => function () use ($mediaItems) {
            return $mediaItems->random()->id;
        }
    ]);
}

}
