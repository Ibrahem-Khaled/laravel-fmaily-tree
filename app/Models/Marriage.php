<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    use HasFactory;

    protected $fillable = [
        'husband_id',
        'wife_id',
        'married_at',
        'divorced_at',
        'is_divorced',
    ];
    protected $casts = [
        'married_at' => 'date',
        'divorced_at' => 'date',
        'is_divorced' => 'boolean',
    ];

    public function husband()
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }

    /**
     * تحديد ما إذا كان الزواج مطلق
     */
    public function isDivorced(): bool
    {
        return $this->is_divorced || !is_null($this->divorced_at);
    }

    /**
     * تحديد حالة الزواج
     */
    public function getStatusAttribute(): string
    {
        if ($this->isDivorced()) {
            return 'divorced';
        } elseif (!is_null($this->married_at)) {
            return 'active';
        } else {
            return 'unknown';
        }
    }

    /**
     * الحصول على نص الحالة باللغة العربية
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'divorced' => 'منفصل',
            'active' => 'نشط',
            'unknown' => 'غير محدد',
            default => 'غير محدد'
        };
    }
}
