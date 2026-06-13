<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. جلب قسم عشوائي موجود مسبقاً في قاعدة البيانات، وإذا لم يجد ينشئ واحداً
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        
        // 2. تحويل اسم القسم إلى حروف صغيرة لتسهيل التحقق (مثال: Nature تصبح nature)
        $keyword = strtolower($category->name);
        
        // 3. الرابط الاحتياطي العام في حال لم يتطابق الاسم مع الشروط
        $realImagePath = "https://unsplash.com" . rand(1, 1000);
        
        // 4. حقن روابط صور حقيقية، فخمة، وثابتة من سيرفرات Unsplash بناءً على نوع القسم
        // إضافة الحقل sig تضمن توليد صورة مختلفة لكل كارت في الموقع وتمنع تكرار نفس الصورة
        if (str_contains($keyword, 'nature')) {
            $realImagePath = "https://unsplash.com" . rand(1, 1000);
        } elseif (str_contains($keyword, 'tech')) {
            $realImagePath = "https://unsplash.com" . rand(1, 1000);
        } elseif (str_contains($keyword, 'animal')) {
            $realImagePath = "https://unsplash.com" . rand(1, 1000);
        } elseif (str_contains($keyword, 'architect') || str_contains($keyword, 'build')) {
            $realImagePath = "https://unsplash.com" . rand(1, 1000);
        } elseif (str_contains($keyword, 'busines')) {
            $realImagePath = "https://unsplash.com" . rand(1, 1000);
        }

        return [
            // جلب مستخدم عشوائي لربط الصورة به
            'user_id' => User::factory(),
            'category_id' => $category->id,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'file_path' => $realImagePath, // هنا سيتم حقن الرابط الحقيقي الذكي
            'file_type' => 'image',
            'downloads_count' => rand(0, 150),
        ];
    }
}