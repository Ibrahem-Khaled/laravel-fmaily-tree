<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'category_id', 'person_id'];

    // علاقة لجلب الفئة التي ينتمي إليها المقال
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة لجلب الشخص المرتبط بالمقال (إذا وجد)
    public function person()
    {
        return $this->belongsTo(Person::class); // افترض أن لديك موديل Person
    }

    // علاقة لجلب كل صور المقال
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
