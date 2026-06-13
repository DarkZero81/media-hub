<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['name', 'slug']; // للسماح بالإدخال الجماعي

        public function media() {
          return $this->hasMany(Media::class);
}

}
