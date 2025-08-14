<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image', 'parent_id'];

    // علاقة لجلب الفئة الأب
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // علاقة لجلب الفئات الفرعية
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // علاقة لجلب كل المقالات التابعة لهذه الفئة
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
