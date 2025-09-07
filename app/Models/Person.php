<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\Scopes\OrderedScope;

class Person extends Model
{
    use HasFactory, NodeTrait;
    protected $table = 'persons';
    protected $fillable = [
        'display_order', // أضف هذا الحقل
        'first_name',
        'last_name',
        'birth_date',
        'death_date',
        'gender',
        'from_outside_the_family',
        'photo_url',
        'biography',
        'occupation',
        'location',
        'parent_id',
        'mother_id',
    ];
    protected $appends = [
        'full_name',
        'articles_count',
        'first_article_id'
    ];
    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];
    protected static function booted()
    {
        // ✅ الخطوة 2: تطبيق الـ Scope بشكل دائم على الموديل
        static::addGlobalScope(new OrderedScope);
    }


    public function getFullNameAttribute(): string
    {
        // ابدأ بالاسم الأول للشخص نفسه
        $nameParts = [$this->first_name];

        // ابدأ من الشخص الحالي وتتبع سلالة الأب
        $currentPerson = $this;
        $ancestor = $this->parent;

        // استمر في الصعود في شجرة النسب طالما يوجد أب
        while ($ancestor) {
            // === بداية التعديل ===
            // تحقق من أن الجد ذكر لمواصلة سلسلة النسب الذكورية
            if ($ancestor->gender == 'male') {

                // تحديد كلمة النسب بناءً على جنس الابن/الابنة في هذه العلاقة
                // إذا كان الشخص الحالي في الحلقة أنثى، استخدم "بنت"
                // إذا كان ذكرًا، استخدم "بن"
                $relationWord = ($currentPerson->gender === 'female') ? 'بنت' : 'بن';

                // أضف كلمة النسب واسم الجد
                $nameParts[] = $relationWord . ' ' . $ancestor->first_name;
            } else {
                // إذا كان الجد أنثى، فإن سلسلة النسب الذكورية تنقطع هنا
                break;
            }
            // === نهاية التعديل ===

            // انتقل إلى الجد الذي يليه، وقم بتحديث الشخص الحالي إلى الجد السابق
            // لتحديد كلمة النسب الصحيحة في الحلقة التالية
            $currentPerson = $ancestor;
            $ancestor = $ancestor->parent;
        }

        // ادمج أجزاء الاسم مع مسافات بينها للحصول على الاسم الكامل
        return implode(' ', $nameParts);
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

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    public function padges()
    {
        // جدول pivot عندك اسمه person_padges
        return $this->belongsToMany(Padge::class, 'person_padges', 'person_id', 'padge_id')
            ->withTimestamps()
            ->withPivot('is_active');
    }


    // Accessor لجلب عدد المقالات
    public function getArticlesCountAttribute()
    {
        // نستخدم الخاصية المحملة مسبقًا إذا كانت موجودة لتجنب استعلامات إضافية
        if ($this->relationLoaded('articles')) {
            return $this->articles->count();
        }
        // هذا يعمل كحل بديل إذا لم يتم تحميل العلاقة
        return $this->articles()->count();
    }

    // Accessor لجلب ID أول مقال
    public function getFirstArticleIdAttribute()
    {
        if ($this->relationLoaded('articles') && $this->articles->isNotEmpty()) {
            return $this->articles->first()->id;
        }
        // هذا يعمل كحل بديل إذا لم يتم تحميل العلاقة
        return optional($this->articles()->first())->id;
    }
}
