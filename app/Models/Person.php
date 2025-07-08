<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Person extends Model
{
    use HasFactory, NodeTrait;
    protected $table = 'persons';
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'death_date',
        'gender',
        'photo_url',
        'biography',
        'occupation',
        'location',
        'parent_id',
        'mother_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];
    public function getFullNameAttribute(): string
    {
        $names = [$this->first_name];
        $current = $this->parent;

        while ($current) {
            $names[] = "بن " . $current->first_name;
            $current = $current->parent;
        }

        return implode(' ', $names);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        $endDate = $this->death_date ?? now();
        return $this->birth_date->diffInYears($endDate);
    }

    public function getAvatarAttribute(): ?string
    {
        if ($this->photo_url) {
            return asset('storage/' . $this->photo_url);
        }

        // استخدام صورة افتراضية إذا لم يكن هناك صورة
        return $this->gender == 'male' ? asset('assets/img/avatar-male.png') : asset('assets/img/avatar-female.png');
    }

    // Relationships

    public function children()
    {
        return $this->hasMany(Person::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Person::class, 'parent_id');
    }

    public function mother()
    {
        return $this->belongsTo(Person::class, 'mother_id');
    }

    public function childrenFromMother()
    {
        return $this->hasMany(Person::class, 'mother_id');
    }


    public function wives()
    {
        return $this->hasManyThrough(Person::class, Marriage::class, 'husband_id', 'id', 'id', 'wife_id');
    }

    // الزوجة (لها زوج واحد)
    public function husband()
    {
        return $this->hasOneThrough(Person::class, Marriage::class, 'wife_id', 'id', 'id', 'husband_id');
    }
}
