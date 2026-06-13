<?php

namespace App\Models;

// 1. استدعاء المكتبات يجب أن يكون هنا دائماً في أعلى الملف خارج الكلاس
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; // الاستدعاء الصحيح لمكتبة التخزين في أعلى الملف هنا

class User extends Authenticatable
{
    // 2. داخل الكلاس نضع فقط الـ Traits الخاصة بـ Laravel
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar', // تأكد من إضافة حقل الـ avatar هنا أيضاً للسماح بالإدخال الجماعي
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // دالة جلب رابط الـ Avatar الذكية المبرمجة سابقاً
public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        return \Illuminate\Support\Facades\Storage::url($this->avatar);
    }

    // This clean format prevents any theme data or dark classes from breaking the domain string
    return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4f46e5&color=fff&rounded=true&bold=true';
}


    // علاقات الجداول (المفضلة والوسائط)
    public function media() {
        return $this->hasMany(Media::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function favoriteMedia()
    {
        return $this->belongsToMany(Media::class, 'favorites')->withTimestamps();
    }
}
