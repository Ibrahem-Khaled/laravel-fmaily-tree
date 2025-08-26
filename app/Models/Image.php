<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'path', 'article_id', 'category_id'];

    // علاقة لجلب المقال الذي تنتمي إليه الصورة
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the category that owns the image.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
