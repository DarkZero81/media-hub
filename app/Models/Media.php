<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // هذا هو السطر الحاسم الذي يجب إضافته هنا

class Media extends Model
{
    use HasFactory; 

    protected $fillable = ['user_id', 'category_id', 'title', 'description', 'file_path', 'file_type', 'downloads_count'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
    public function likedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
}

// دالة ذكية لفحص هل المستخدم الحالي معجب بهذه الصورة أم لا (ستفيدنا في تلوين الزر)
public function isLikedBy($user)
{
    if (!$user) return false;
    return $this->likedByUsers()->where('user_id', $user->id)->exists();
}

}
