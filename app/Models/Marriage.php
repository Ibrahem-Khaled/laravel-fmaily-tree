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
    ];
    protected $casts = [
        'married_at' => 'date',
        'divorced_at' => 'date',
    ];

    public function husband()
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }
}
