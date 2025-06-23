<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Person extends Model
{
    use HasFactory, NodeTrait;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persons';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];
    
    /**
     * Get the full name of the person.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    /**
     * Get the person's age or age at death.
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        
        $endDate = $this->death_date ?? now();
        return $this->birth_date->diffInYears($endDate);
    }
    
    /**
     * Get all children of this person.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function children()
    {
        return $this->hasMany(Person::class, 'parent_id');
    }
    
    /**
     * Get the parent of this person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Person::class, 'parent_id');
    }
}
