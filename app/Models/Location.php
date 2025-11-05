<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Location extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'normalized_name',
        'description',
        'country',
        'city',
        'persons_count',
    ];

    protected $casts = [
        'persons_count' => 'integer',
    ];

    protected static function booted()
    {
        // إنشاء normalized_name تلقائياً عند الإنشاء أو التحديث
        static::saving(function ($location) {
            if (empty($location->normalized_name) || $location->isDirty('name')) {
                $location->normalized_name = self::normalizeName($location->name);
            }
        });
    }

    /**
     * تطبيع اسم المكان (إزالة المسافات الزائدة، تحويل للأحرف الصغيرة، إزالة علامات الترقيم)
     */
    public static function normalizeName(string $name): string
    {
        $normalized = trim($name);
        // تنظيف الفواصل الزائدة أولاً (استبدال الفواصل بمسافات)
        $normalized = preg_replace('/\s*,\s*/', ' ', $normalized);
        $normalized = mb_strtolower($normalized, 'UTF-8');
        // إزالة المسافات المتعددة
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        // إزالة علامات الترقيم الزائدة (لكن نحتفظ بالمسافات)
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normalized);
        return trim($normalized);
    }

    /**
     * البحث عن أماكن مشابهة (للدمج)
     */
    public static function findSimilar(string $name, float $threshold = 0.8): array
    {
        $normalized = self::normalizeName($name);
        $locations = self::all();
        $similar = [];

        foreach ($locations as $location) {
            $similarity = self::calculateSimilarity($normalized, $location->normalized_name);
            if ($similarity >= $threshold) {
                $similar[] = [
                    'location' => $location,
                    'similarity' => $similarity
                ];
            }
        }

        // ترتيب حسب التشابه
        usort($similar, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return $similar;
    }

    /**
     * حساب نسبة التشابه بين نصين (Levenshtein distance)
     */
    private static function calculateSimilarity(string $str1, string $str2): float
    {
        $len1 = mb_strlen($str1);
        $len2 = mb_strlen($str2);

        if ($len1 === 0 || $len2 === 0) {
            return 0.0;
        }

        // إذا كانا متطابقين تماماً
        if ($str1 === $str2) {
            return 1.0;
        }

        // حساب Levenshtein distance
        $distance = levenshtein($str1, $str2);
        $maxLen = max($len1, $len2);

        // تحويل المسافة إلى نسبة تشابه (0-1)
        return 1 - ($distance / $maxLen);
    }

    /**
     * البحث عن مكان أو إنشاؤه إذا لم يكن موجوداً
     */
    public static function findOrCreateByName(string $name): self
    {
        $normalized = self::normalizeName($name);
        
        $location = self::where('normalized_name', $normalized)->first();
        
        if (!$location) {
            // البحث عن أماكن مشابهة قبل الإنشاء
            $similar = self::findSimilar($name, 0.9);
            
            if (!empty($similar) && $similar[0]['similarity'] >= 0.95) {
                // إذا كان هناك مكان مشابه جداً (95%+)، نستخدمه بدلاً من إنشاء جديد
                return $similar[0]['location'];
            }
            
            $location = self::create([
                'name' => trim($name),
                'normalized_name' => $normalized,
            ]);
        }
        
        return $location;
    }

    // العلاقات
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    // Scopes
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('normalized_name', 'like', "%" . self::normalizeName($term) . "%")
              ->orWhere('country', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%");
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('persons_count', 'desc')
                    ->orderBy('name');
    }

    /**
     * Accessor لعرض الاسم بشكل منظم
     * تنظيف الاسم من الفواصل الزائدة وتحسين التنسيق
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->name;
        
        // تنظيف الفواصل الزائدة (استبدال الفواصل بمسافات)
        $name = preg_replace('/\s*,\s*/', ' ', $name);
        
        // إزالة المسافات المتعددة
        $name = preg_replace('/\s+/', ' ', $name);
        
        // تنظيف المسافات في البداية والنهاية
        $name = trim($name);
        
        // إذا كان الاسم يحتوي على "السعوديه" أو "السعودية" في النهاية، نعيد تنسيقه
        // مثال: "بلد الروضه السعوديه" -> "بلد الروضه، السعودية"
        if (preg_match('/\s+(السعوديه|السعودية)$/u', $name, $matches)) {
            $name = preg_replace('/\s+(السعوديه|السعودية)$/u', '، السعودية', $name);
        }
        
        return $name;
    }

    /**
     * Accessor لعرض الاسم مع الدولة (إذا كانت موجودة)
     */
    public function getFullDisplayNameAttribute(): string
    {
        $name = $this->display_name;
        
        // إذا كانت الدولة موجودة وليست جزء من الاسم، نضيفها
        if ($this->country && mb_stripos($name, $this->country) === false) {
            $name .= '، ' . $this->country;
        }
        
        return $name;
    }
}
