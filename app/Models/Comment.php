<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // أضفه هنا أيضاً

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'media_id', 'body'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function media() {
        return $this->belongsTo(Media::class);
    }
}
