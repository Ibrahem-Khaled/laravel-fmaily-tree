<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonContactAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type',
        'value',
        'label',
        'sort_order',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * الحصول على أيقونة حسب نوع الحساب
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'phone' => 'fa-phone',
            'whatsapp' => 'fa-whatsapp',
            'email' => 'fa-envelope',
            'facebook' => 'fa-facebook',
            'instagram' => 'fa-instagram',
            'twitter' => 'fa-twitter',
            'linkedin' => 'fa-linkedin',
            'telegram' => 'fa-telegram',
            default => 'fa-link',
        };
    }

    /**
     * الحصول على رابط حسب نوع الحساب
     */
    public function getUrlAttribute(): ?string
    {
        return match($this->type) {
            'phone' => 'tel:' . $this->value,
            'whatsapp' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $this->value),
            'email' => 'mailto:' . $this->value,
            'facebook' => str_starts_with($this->value, 'http') ? $this->value : 'https://facebook.com/' . $this->value,
            'instagram' => str_starts_with($this->value, 'http') ? $this->value : 'https://instagram.com/' . $this->value,
            'twitter' => str_starts_with($this->value, 'http') ? $this->value : 'https://twitter.com/' . $this->value,
            'linkedin' => str_starts_with($this->value, 'http') ? $this->value : 'https://linkedin.com/in/' . $this->value,
            'telegram' => str_starts_with($this->value, 'http') ? $this->value : 'https://t.me/' . $this->value,
            default => $this->value,
        };
    }
}
