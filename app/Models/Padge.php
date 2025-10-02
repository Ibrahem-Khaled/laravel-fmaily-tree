<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Padge extends BaseModel
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
        return $this->belongsToMany(Person::class, 'person_padges', 'padge_id', 'person_id')
            ->withTimestamps()
            ->withPivot('is_active');
    }
}
