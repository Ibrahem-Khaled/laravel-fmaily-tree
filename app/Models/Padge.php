<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Padge extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'color',
        'sort_order',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function people()
    {
        return $this->belongsToMany(Person::class, 'person_padges')->withTimestamps()
            ->withPivot('is_active');
    }
}
