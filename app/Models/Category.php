<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image', 'sort_order', 'parent_id'];

    protected static function booted()
    {
        // استخدام الحدث 'creating' الذي يتم إطلاقه قبل إنشاء سجل جديد
        static::creating(function ($category) {
            // التحقق إذا كان حقل sort_order لم يتم تعيينه بالفعل
            if (is_null($category->sort_order)) {
                // البحث عن أعلى قيمة لـ sort_order في نفس المستوى (نفس parent_id)
                $maxSortOrder = self::where('parent_id', $category->parent_id)->max('sort_order');

                // تعيين القيمة الجديدة لتكون +1 من أعلى قيمة موجودة
                // إذا لم يكن هناك فئات بعد (maxSortOrder is null)، فستكون القيمة 0
                $category->sort_order = ($maxSortOrder === null) ? 0 : $maxSortOrder + 1;
            }
        });
    }

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
