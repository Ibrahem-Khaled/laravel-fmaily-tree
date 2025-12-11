<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'location_id',
        'label',
        'url',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
