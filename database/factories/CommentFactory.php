<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'user_id' => \App\Models\User::factory(),
        'media_id' => \App\Models\Media::factory(),
        'body' => $this->faker->sentence(10), // تعليق عشوائي من 10 كلمات
    ];
}

}
