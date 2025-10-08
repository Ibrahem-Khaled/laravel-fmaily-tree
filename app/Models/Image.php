<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Image extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'path',
        'article_id',
        'category_id'
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function mentionedPersons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'image_mentions', 'image_id', 'person_id')
            ->withTimestamps()
            ->withPivot('order')
            ->orderBy('image_mentions.order');
    }

    protected static function booted(): void
    {
        static::deleting(function (Image $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
        });
    }
}
