<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteContent extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'key',
        'content',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    /**
     * الحصول على المحتوى بناءً على المفتاح
     */
    public static function getContent($key, $default = '')
    {
        $content = self::where('key', $key)
            ->where('is_active', true)
            ->first();
        
        return $content ? $content->content : $default;
    }
}
