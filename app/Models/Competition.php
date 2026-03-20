<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Competition extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'game_type',
        'team_size',
        'registration_token',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'program_id',
        'bracket_round_winners',
        'bracket_manual_opponents',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'team_size' => 'integer',
        'bracket_round_winners' => 'array',
        'bracket_manual_opponents' => 'array',
    ];

    /**
     * توليد رمز التسجيل تلقائياً
     */
    protected static function booted(): void
    {
        static::creating(function (Competition $competition) {
            if (empty($competition->registration_token)) {
                $competition->registration_token = Str::random(32);
            }
        });
    }

    /**
     * العلاقة مع الفرق
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * العلاقة مع التسجيلات
     */
    public function registrations()
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    /**
     * العلاقة مع منشئ المسابقة
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * العلاقة مع البرنامج
     */
    public function program()
    {
        return $this->belongsTo(Image::class, 'program_id');
    }

    /**
     * العلاقة مع التصنيفات (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'competition_categories')
            ->withTimestamps()
            ->orderBy('categories.sort_order')
            ->orderBy('categories.name');
    }

    /**
     * Scope للمسابقات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * التحقق من أن المسابقة في فترة التسجيل
     */
    public function isRegistrationOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * الحصول على رابط التسجيل
     */
    public function getRegistrationUrlAttribute(): string
    {
        return route('competitions.register', ['token' => $this->registration_token]);
    }

    /**
     * ترتيب الفرق للمجموعات والـ JSON: ترتيب يبدو عشوائياً لكنه ثابت لكل مسابقة
     * (دالة تحديدية من id المسابقة + id الفريق)، ثم إقران اثنين اثنين.
     */
    public function getTeamsOrderedForBracket(): Collection
    {
        $seedKey = (string) $this->id;
        $teams = $this->relationLoaded('teams')
            ? $this->teams
            : $this->teams()->with('members')->orderBy('id')->get();

        return $teams->sortBy(fn ($team) => hash('sha256', $seedKey . '|' . $team->id))->values();
    }

    /**
     * مجموعات البطولة بنفس ترتيب رابط /competitions/{id}/json.
     *
     * @return Collection<int, array{group_number: int, team_1: ?Team, team_2: ?Team}>
     */
    public function getBracketGroups(): Collection
    {
        $teams = $this->getTeamsOrderedForBracket();

        $groups = collect();
        $total = $teams->count();
        $groupNumber = 1;
        for ($i = 0; $i < $total; $i += 2) {
            $groups->push([
                'group_number' => $groupNumber,
                'team_1' => $teams[$i] ?? null,
                'team_2' => $teams[$i + 1] ?? null,
            ]);
            $groupNumber++;
        }

        return $groups;
    }

    /**
     * تطبيع JSON الفائزين: مفاتيح أرقام صحيحة للجولة والمباراة.
     *
     * @param  array<string|int, mixed>|null  $raw
     * @return array<int, array<int, int>>
     */
    public function normalizeBracketRoundWinners(?array $raw): array
    {
        if ($raw === null || $raw === []) {
            return [];
        }
        $out = [];
        foreach ($raw as $roundKey => $matches) {
            if (!is_array($matches)) {
                continue;
            }
            $r = (int) $roundKey;
            foreach ($matches as $matchKey => $teamId) {
                if ($teamId === null || $teamId === '') {
                    continue;
                }
                $out[$r][(int) $matchKey] = (int) $teamId;
            }
        }

        return $out;
    }

    /**
     * أطوال الجولات (عدد المباريات في كل جولة) من عدد مجموعات الدور الأول.
     *
     * @return int[]
     */
    public function getKnockoutRoundMatchCounts(int $firstRoundMatchCount): array
    {
        if ($firstRoundMatchCount < 1) {
            return [];
        }
        $counts = [];
        $c = $firstRoundMatchCount;
        while ($c >= 1) {
            $counts[] = $c;
            if ($c === 1) {
                break;
            }
            $c = (int) ceil($c / 2);
        }

        return $counts;
    }

    /**
     * نفس بنية الفائزين: [جولة][مباراة] => team_id للمنافس المعيّن يدوياً.
     *
     * @param  array<int, array<int, int>>|null  $raw
     */
    public function normalizeBracketManualOpponents(?array $raw): array
    {
        return $this->normalizeBracketRoundWinners($raw);
    }

    /**
     * شجرة الإقصاء: الفائزون من كل مجموعة يقابلون بعضهم بالترتيب حتى النهائي.
     * عند عدم وجود طرف ثانٍ (فريق فردي)، يُختار المنافس يدوياً من فرق المسابقة وليس إعفاءً تلقائياً.
     *
     * @param  array<int, array<int, int>>|null  $storedOverride
     * @param  array<int, array<int, int>>|null  $manualOverride
     * @return Collection<int, array{round: int, title: string, matches: array<int, array<string, mixed>>}>
     */
    public function getKnockoutRounds(?array $storedOverride = null, ?array $manualOverride = null): Collection
    {
        $this->loadMissing(['teams.members']);
        $teamsById = $this->teams->keyBy('id');
        $stored = $this->normalizeBracketRoundWinners($storedOverride ?? $this->bracket_round_winners ?? []);
        $manual = $this->normalizeBracketManualOpponents($manualOverride ?? $this->bracket_manual_opponents ?? []);

        $groups = $this->getBracketGroups();
        $g = $groups->count();
        if ($g === 0) {
            return collect();
        }

        $counts = $this->getKnockoutRoundMatchCounts($g);
        $rounds = collect();

        foreach ($counts as $idx => $matchCount) {
            $roundNum = $idx + 1;
            $matches = [];

            if ($roundNum === 1) {
                foreach ($groups as $group) {
                    $m = $group['group_number'];
                    $t1 = $group['team_1'];
                    $t2Natural = $group['team_2'];
                    $manualId = $manual[1][$m] ?? null;
                    $t2 = $t2Natural;
                    if (!$t2 && $manualId) {
                        $t2 = $teamsById->get((int) $manualId);
                    }
                    $usesManual = !$t2Natural && $manualId && $t2;
                    $needsManual = $t1 && !$t2Natural && !$manualId;
                    $matches[] = [
                        'round' => 1,
                        'match' => $m,
                        'team_1' => $t1,
                        'team_2' => $t2,
                        'winner_id' => $stored[1][$m] ?? null,
                        'label_1' => null,
                        'label_2' => $t2 ? null : ($needsManual ? 'اختر المنافس من القائمة' : '—'),
                        'has_natural_second' => (bool) $t2Natural,
                        'uses_manual_opponent' => $usesManual,
                        'needs_manual_opponent' => $needsManual,
                    ];
                }
            } else {
                $prevCount = $counts[$idx - 1];
                for ($m = 1; $m <= $matchCount; $m++) {
                    $l = 2 * $m - 1;
                    $r = 2 * $m;
                    $wid1 = $stored[$roundNum - 1][$l] ?? null;
                    $wid2 = $r <= $prevCount ? ($stored[$roundNum - 1][$r] ?? null) : null;
                    $t1 = $wid1 ? $teamsById->get((int) $wid1) : null;
                    $t2Natural = $wid2 ? $teamsById->get((int) $wid2) : null;
                    $manualId = $manual[$roundNum][$m] ?? null;
                    $t2 = $t2Natural;
                    if (!$t2 && $manualId) {
                        $t2 = $teamsById->get((int) $manualId);
                    }
                    $usesManual = !$t2Natural && $manualId && $t2;
                    $needsManual = $t1 && !$t2Natural && !$manualId;
                    $matches[] = [
                        'round' => $roundNum,
                        'match' => $m,
                        'team_1' => $t1,
                        'team_2' => $t2,
                        'winner_id' => $stored[$roundNum][$m] ?? null,
                        'label_1' => $t1 ? null : $this->knockoutPlaceholderLabel($roundNum - 1, $l),
                        'label_2' => $t2 ? null : ($needsManual ? 'اختر المنافس من القائمة' : '—'),
                        'has_natural_second' => (bool) $t2Natural,
                        'uses_manual_opponent' => $usesManual,
                        'needs_manual_opponent' => $needsManual,
                    ];
                }
            }

            $rounds->push([
                'round' => $roundNum,
                'title' => $this->knockoutRoundTitle($roundNum, $matchCount),
                'matches' => $matches,
            ]);
        }

        return $rounds;
    }

    protected function knockoutRoundTitle(int $roundNum, int $matchCount): string
    {
        if ($roundNum === 1) {
            return 'دور المجموعات';
        }
        if ($matchCount === 1) {
            return 'النهائي';
        }
        if ($matchCount === 2) {
            return 'نصف النهائي';
        }
        if ($matchCount === 4) {
            return 'ربع النهائي';
        }

        return 'الدور ' . $roundNum;
    }

    protected function knockoutPlaceholderLabel(int $prevRound, int $prevMatch): string
    {
        if ($prevRound === 1) {
            return 'فائز المجموعة ' . $prevMatch;
        }

        return 'فائز المباراة (الدور ' . $prevRound . ' — مباراة ' . $prevMatch . ')';
    }

    /**
     * إزالة فائزين غير ممكنين (مثلاً بعد تغيير نتيجة جولة سابقة) مع إعادة المحاولة حتى الاستقرار.
     *
     * @param  array<int, array<int, int>>  $stored
     * @return array<int, array<int, int>>
     */
    public function cleanBracketRoundWinners(array $stored): array
    {
        $changed = true;
        while ($changed) {
            $changed = false;
            $rounds = $this->getKnockoutRounds($stored, null);
            foreach ($rounds as $roundData) {
                $r = $roundData['round'];
                foreach ($roundData['matches'] as $match) {
                    $m = $match['match'];
                    if (!isset($stored[$r][$m])) {
                        continue;
                    }
                    $allowed = $this->allowedWinnerIdsForMatch($match);
                    $wid = (int) $stored[$r][$m];
                    if ($allowed === [] || !in_array($wid, $allowed, true)) {
                        unset($stored[$r][$m]);
                        $changed = true;
                    }
                }
            }
            foreach (array_keys($stored) as $r) {
                if (($stored[$r] ?? []) === []) {
                    unset($stored[$r]);
                }
            }
        }

        return $stored;
    }

    /**
     * إزالة اختيار المنافس اليدوي عندما يصبح الطرف الثاني معروفاً من الشجرة (بدون اختيار يدوي).
     *
     * @param  array<int, array<int, int>>  $manual
     * @param  array<int, array<int, int>>  $storedWinners
     * @return array<int, array<int, int>>
     */
    public function cleanBracketManualOpponents(array $manual, array $storedWinners): array
    {
        $manual = $this->normalizeBracketManualOpponents($manual);
        $rounds = $this->getKnockoutRounds($storedWinners, $manual);
        foreach ($rounds as $roundData) {
            foreach ($roundData['matches'] as $match) {
                $r = $match['round'];
                $m = $match['match'];
                if (empty($match['uses_manual_opponent'])) {
                    unset($manual[$r][$m]);
                }
            }
        }
        foreach (array_keys($manual) as $r) {
            if (($manual[$r] ?? []) === []) {
                unset($manual[$r]);
            }
        }

        return $manual;
    }

    /**
     * @param  array<string, mixed>  $match
     * @return int[]
     */
    public function allowedWinnerIdsForMatch(array $match): array
    {
        $ids = [];
        if (!empty($match['team_1']) && $match['team_1']->id) {
            $ids[] = (int) $match['team_1']->id;
        }
        if (!empty($match['team_2']) && $match['team_2']->id) {
            $ids[] = (int) $match['team_2']->id;
        }

        return array_values(array_unique($ids));
    }
}
