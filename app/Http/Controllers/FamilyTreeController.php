<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Marriage;
use Illuminate\Http\Request;

class FamilyTreeController extends Controller
{
    // عرض تواصل العائلة الرئيسية
    public function index()
    {
        return view('old_index');
    }
    public function newIndex()
    {
        return view('new_index');
    }

    // API لجلب تواصل العائلة
    public function getFamilyTree()
    {
        // استخدام cache لتحسين الأداء
        $cacheKey = 'family_tree_data';
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            return response()->json([
                'success' => true,
                'tree' => $cachedData,
                'cached' => true
            ]);
        }

        // الحصول على الجذور مع تحسين الأداء - تحديد الحقول المطلوبة فقط
        $roots = Person::select([
            'id', 'first_name', 'last_name', 'gender', 'birth_date', 'birth_place', 'death_date',
            'death_place', 'cemetery', 'photo_url', 'parent_id', 'mother_id', 'from_outside_the_family'
        ])
        ->whereNull('parent_id')
        ->where('from_outside_the_family', false)
        ->with([
            'children:id,first_name,last_name,gender,birth_date,birth_place,death_date,death_place,cemetery,photo_url,parent_id,mother_id',
            'childrenFromMother:id,first_name,last_name,gender,birth_date,birth_place,death_date,death_place,cemetery,photo_url,parent_id,mother_id'
        ])
        ->withCount('mentionedImages')
        ->get();

        // تنظيم البيانات بشكل شجري محسن
        $tree = $this->buildTreeOptimized($roots);

        // حفظ البيانات في cache لمدة 30 دقيقة
        cache()->put($cacheKey, $tree, 1800);

        return response()->json([
            'success' => true,
            'tree' => $tree,
            'cached' => false
        ]);
    }

    public function addSelf()
    {
        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();
        return view('add-self', [
            'males' => $males,
            'females' => $females,
        ]);
    }

    private function buildTreeOptimized($people)
    {
        return $people->map(function ($person) {
            $data = $this->formatPersonData($person);

            // حساب عدد الأبناء بناءً على الجنس - استخدام البيانات المحملة مسبقاً
            $childrenCount = 0;
            if ($person->gender === 'female') {
                // للإناث: لا نعرض أبناء الأمهات في الشجرة الرئيسية
                $childrenCount = 0;
            } else {
                // للذكور: استخدام العلاقة المحملة مسبقاً
                $childrenCount = $person->children ? $person->children->count() : 0;
            }

            $data['children_count'] = $childrenCount;

            return $data;
        });
    }

    private function buildTree($people)
    {
        return $people->map(function ($person) {
            $data = $this->formatPersonData($person);

            // حساب عدد الأبناء بناءً على الجنس - استخدام البيانات المحملة مسبقاً
            $childrenCount = 0;
            if ($person->gender === 'female') {
                // للإناث: لا نعرض أبناء الأمهات في الشجرة الرئيسية
                $childrenCount = 0;
            } else {
                // للذكور: استخدام العلاقة المحملة مسبقاً
                $childrenCount = $person->children ? $person->children->count() : 0;
            }

            // إذا كان للشخص أبناء، نضيفهم بشكل متداخل
            if ($childrenCount > 0) {
                if ($person->gender === 'female') {
                    // للإناث: لا نعرض أبناء الأمهات في الشجرة الرئيسية
                    $children = collect();
                } else {
                    // للذكور: استخدام البيانات المحملة مسبقاً
                    $children = $person->children ?: collect();
                }

                $data['children'] = $this->buildTree($children);
                $data['children_count'] = $childrenCount;
            }

            return $data;
        });
    }

    // API لجلب أبناء شخص معين
    public function getChildren($id)
    {
        // استخدام cache لتحسين الأداء
        $cacheKey = "person_children_{$id}";
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            return response()->json([
                'success' => true,
                'children' => $cachedData,
                'cached' => true
            ]);
        }

        $person = Person::select(['id', 'gender'])->findOrFail($id);
        $childrenQuery = Person::select([
            'id', 'first_name', 'last_name', 'gender', 'birth_date', 'birth_place', 'death_date',
            'death_place', 'cemetery', 'photo_url', 'parent_id', 'mother_id', 'location_id'
        ]);

        // التحقق من جنس الشخص لتحديد كيفية البحث عن الأبناء
        if ($person->gender === 'female') {
            // للإناث: لا نعرض أبناء الأمهات في الشجرة، نرجع قائمة فارغة
            $childrenQuery->whereRaw('1 = 0'); // استعلام فارغ
        } else {
            // إذا كان الشخص ذكر، يتم البحث عن طريق parent_id (الأب)
            $childrenQuery->where('parent_id', $person->id);
        }

        // جلب الأبناء مع عدد أبنائهم محسن للأداء
        $children = $childrenQuery->with('location:id,name,normalized_name') // تحميل علاقة location
            ->withCount([
            'children as children_count',
            'childrenFromMother as children_from_mother_count'
        ])->get();

        $childrenData = array_values($children->map(function (Person $child) {
            $childData = $this->formatPersonData($child);
            // إظهار العدد الصحيح للذكور والإناث
            if ($child->gender === 'female') {
                $childData['children_count'] = $child->children_from_mother_count ?? 0;
            } else {
                $childData['children_count'] = $child->children_count ?? 0;
            }
            return $childData;
        })->toArray());

        // حفظ البيانات في cache لمدة 15 دقيقة
        cache()->put($cacheKey, $childrenData, 900);

        return response()->json([
            'success' => true,
            'children' => $childrenData,
            'cached' => false
        ]);
    }

    // API لجلب تفاصيل شخص معين
    public function getPersonDetails($id)
    {
        // استخدام cache لتحسين الأداء
        $cacheKey = "person_details_{$id}";
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            return response()->json([
                'success' => true,
                'person' => $cachedData,
                'cached' => true
            ]);
        }

        // Eager load relationships محسن للأداء - تحديد الحقول المطلوبة فقط
        $person = Person::select([
            'id', 'first_name', 'last_name', 'gender', 'birth_date', 'birth_place', 'death_date',
            'death_place', 'cemetery', 'cemetery_location', 'grave_number', 'photo_url', 'biography', 'occupation', 'location_id', 'parent_id', 'mother_id'
        ])
        ->with([
            'parent:id,first_name,last_name,gender,birth_date,death_date,photo_url',
            'mother:id,first_name,last_name,gender,birth_date,death_date,photo_url',
            'location:id,name,normalized_name', // تحميل علاقة location
            'articles:id,title,person_id',
            'friendships.friend:id,first_name,last_name,gender,birth_date,death_date,photo_url',
            'contactAccounts:id,person_id,type,value,label,sort_order', // حسابات التواصل
            'locations:id,name,normalized_name' // لوكيشنات متعددة
        ])
        ->withCount(['mentionedImages', 'friendships'])
        ->findOrFail($id);

        $personData = $this->formatPersonData($person, true);

        // في كارد التفاصيل، نضمن عرض العدد الصحيح للأبناء (بما في ذلك أبناء الأمهات)
        if ($person->gender === 'female') {
            $personData['children_count'] = Person::where('mother_id', $person->id)->count();
        } else {
            $personData['children_count'] = $person->children()->count();
        }

        // حفظ البيانات في cache لمدة 20 دقيقة
        cache()->put($cacheKey, $personData, 1200);

        return response()->json([
            'success' => true,
            'person' => $personData,
            'cached' => false
        ]);
    }

    // API لجلب أبناء شخص معين في كارد التفاصيل (يشمل أبناء الأمهات)
    public function getChildrenForDetails($id)
    {
        $cacheKey = "person_children_details_{$id}";
        $cachedData = cache()->get($cacheKey);

        if ($cachedData) {
            return response()->json([
                'success' => true,
                'children' => $cachedData,
                'cached' => true
            ]);
        }

        $person = Person::select(['id', 'gender'])->findOrFail($id);
        $childrenQuery = Person::select([
            'id', 'first_name', 'last_name', 'gender', 'birth_date', 'birth_place', 'death_date',
            'death_place', 'cemetery', 'photo_url', 'parent_id', 'mother_id', 'location_id'
        ]);

        // التحقق من جنس الشخص لتحديد كيفية البحث عن الأبناء
        if ($person->gender === 'female') {
            // للإناث: البحث عن الأبناء عن طريق mother_id
            $childrenQuery->where('mother_id', $person->id);
        } else {
            // للذكور: البحث عن طريق parent_id (الأب)
            $childrenQuery->where('parent_id', $person->id);
        }

        // جلب الأبناء مع عدد أبنائهم محسن للأداء
        $children = $childrenQuery->with('location:id,name,normalized_name') // تحميل علاقة location
            ->withCount([
            'children as children_count',
            'childrenFromMother as children_from_mother_count'
        ])->get();

        $childrenData = array_values($children->map(function (Person $child) {
            $childData = $this->formatPersonData($child);
            // إظهار العدد الصحيح للذكور والإناث
            if ($child->gender === 'female') {
                $childData['children_count'] = $child->children_from_mother_count ?? 0;
            } else {
                $childData['children_count'] = $child->children_count ?? 0;
            }
            return $childData;
        })->toArray());

        // حفظ البيانات في cache لمدة 15 دقيقة
        cache()->put($cacheKey, $childrenData, 900);

        return response()->json([
            'success' => true,
            'children' => $childrenData,
            'cached' => false
        ]);
    }

    // API لجلب زوجات شخص معين
    public function getWives($fatherId)
    {
        $father = Person::findOrFail($fatherId);

        // استخدام العلاقة 'wives' الموجودة في موديل Person لجلب الزوجات
        $wives = $father->wives()->get();

        // إرجاع قائمة الزوجات كـ JSON
        return response()->json($wives);
    }

    // تنسيق بيانات الشخص للاستجابة
    private function formatPersonData(Person $person, $fullDetails = false)
    {
        // Calculate children count based on gender - استخدام البيانات المحملة مسبقاً
        if (isset($person->children_count)) {
            $children_count = $person->children_count;
        } elseif (isset($person->children_from_mother_count)) {
            $children_count = $person->children_from_mother_count;
        } else {
            // If not preloaded with withCount, calculate it now.
            if ($person->gender === 'female') {
                $children_count = Person::where('mother_id', $person->id)->count();
            } else {
                $children_count = $person->children()->count();
            }
        }

        // للشجرة الرئيسية: لا نعرض أبناء الأمهات
        if (!$fullDetails && $person->gender === 'female') {
            $children_count = 0;
        }

        $data = [
            'id' => $person->id,
            'parent_id' => $person->parent_id,
            'mother_id' => $person->mother_id,
            'full_name' => $person->full_name,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name, // إضافة last_name للأشخاص من خارج العائلة
            'gender' => $person->gender,
            'photo_url' => $person->avatar, // Assuming 'avatar' is the attribute for photo url
            'children_count' => $children_count, // إظهار العدد الصحيح للذكور والإناث
            'images_count' => $person->mentioned_images_count ?? $person->mentionedImages()->count(), // عدد الصور المرتبطة بالشخص
            'birth_date' => optional($person->birth_date)->format('Y/m/d'),
            'birth_place' => $person->birth_place,
            'death_date' => optional($person->death_date)->format('Y/m/d'), // Send death date to frontend
            'death_place' => $person->death_place ?? null,
            'cemetery' => $person->cemetery ?? null,
            'cemetery_location' => $person->cemetery_location ?? null,
            'grave_number' => $person->grave_number ?? null,
            'age' => $person->age,
        ];

        if ($fullDetails) {
            $data['occupation'] = $person->occupation;
            $data['location'] = $person->location_display ?? null; // استخدام location_display accessor
            $data['biography'] = $person->biography;

            // إضافة حسابات التواصل
            if ($person->relationLoaded('contactAccounts')) {
                $data['contact_accounts'] = $person->contactAccounts->map(function($account) {
                    return [
                        'id' => $account->id,
                        'type' => $account->type,
                        'value' => $account->value,
                        'label' => $account->label,
                        'icon' => $account->icon,
                        'url' => $account->url,
                    ];
                })->toArray();
            }

            // إضافة لوكيشنات متعددة
            if ($person->relationLoaded('locations')) {
                $data['locations'] = $person->locations->map(function($location) {
                    return [
                        'id' => $location->id,
                        'name' => $location->name,
                        'label' => $location->pivot->label,
                        'is_primary' => $location->pivot->is_primary,
                    ];
                })->toArray();
            }

            if ($person->parent) {
                // To display father's name under main person's name
                $data['parent_name'] = $person->parent->full_name;
                // Add full father object for details card, formatted with basic details
                $data['parent'] = $this->formatPersonData($person->parent, false);
            }

            if ($person->mother) {
                // Add full mother object for details card, formatted with basic details
                $data['mother'] = $this->formatPersonData($person->mother, false);
            }

            if ($person->relationLoaded('articles')) {
                $data['articles'] = $person->articles->map(function ($article) {
                    // نختار فقط الحقول التي نحتاجها في الواجهة الأمامية
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        // يمكنك إضافة حقول أخرى إذا احتجتها
                    ];
                });
            }

            // Format spouses محسن للأداء - نعرض الزوج/الزوجات النشطين فقط (غير المنفصلين)
            // نستخدم دالة isDivorced() للتحقق من كلا الشرطين: is_divorced أو divorced_at
            $spouses = collect();

            if ($person->gender === 'male') {
                $allMarriages = Marriage::select(['id', 'husband_id', 'wife_id', 'married_at', 'divorced_at', 'is_divorced'])
                    ->where('husband_id', $person->id)
                    ->orderBy('married_at')
                    ->get();

                // فلترة الزيجات النشطة فقط (غير المنفصلة)
                $activeMarriages = $allMarriages->filter(function ($marriage) {
                    return !$marriage->isDivorced();
                });

                if ($activeMarriages->isNotEmpty()) {
                    $wifeIds = $activeMarriages->pluck('wife_id');
                    $wives = Person::select(['id', 'first_name', 'last_name', 'gender', 'birth_date', 'birth_place', 'death_date', 'photo_url', 'parent_id'])
                        ->whereIn('id', $wifeIds)
                        ->with(['parent' => function($q1) {
                            // تحميل parent بشكل متداخل لضمان عمل full_name بشكل صحيح
                            $q1->select(['id', 'first_name', 'gender', 'parent_id'])
                               ->with(['parent' => function($q2) {
                                   $q2->select(['id', 'first_name', 'gender', 'parent_id'])
                                      ->with(['parent' => function($q3) {
                                          $q3->select(['id', 'first_name', 'gender', 'parent_id'])
                                             ->with(['parent' => function($q4) {
                                                 $q4->select(['id', 'first_name', 'gender', 'parent_id'])
                                                    ->with(['parent' => function($q5) {
                                                        $q5->select(['id', 'first_name', 'gender', 'parent_id']);
                                                    }]);
                                             }]);
                                      }]);
                               }]);
                        }])
                        ->get();
                    $spouses = $wives;
                }
            } elseif ($person->gender === 'female') {
                $allMarriages = Marriage::select(['id', 'husband_id', 'wife_id', 'married_at', 'divorced_at', 'is_divorced'])
                    ->where('wife_id', $person->id)
                    ->orderByDesc('married_at')
                    ->get();

                // فلترة الزيجات النشطة فقط (غير المنفصلة)
                $activeMarriages = $allMarriages->filter(function ($marriage) {
                    return !$marriage->isDivorced();
                });

                $currentMarriage = $activeMarriages->first();

                if ($currentMarriage && $currentMarriage->husband_id) {
                    $husband = Person::select(['id', 'first_name', 'last_name', 'gender', 'birth_date', 'birth_place', 'death_date', 'photo_url', 'parent_id'])
                        ->with(['parent' => function($q1) {
                            // تحميل parent بشكل متداخل لضمان عمل full_name بشكل صحيح
                            $q1->select(['id', 'first_name', 'gender', 'parent_id'])
                               ->with(['parent' => function($q2) {
                                   $q2->select(['id', 'first_name', 'gender', 'parent_id'])
                                      ->with(['parent' => function($q3) {
                                          $q3->select(['id', 'first_name', 'gender', 'parent_id'])
                                             ->with(['parent' => function($q4) {
                                                 $q4->select(['id', 'first_name', 'gender', 'parent_id'])
                                                    ->with(['parent' => function($q5) {
                                                        $q5->select(['id', 'first_name', 'gender', 'parent_id']);
                                                    }]);
                                             }]);
                                      }]);
                               }]);
                        }])
                        ->find($currentMarriage->husband_id);
                    if ($husband) {
                        $spouses->push($husband);
                    }
                }
            }

            $data['spouses'] = $spouses->map(function ($spouse) {
                // Format spouse using basic details to avoid deep nesting
                return $this->formatPersonData($spouse, false);
            });
        }

        // إضافة الأصدقاء
        if ($fullDetails && $person->relationLoaded('friendships')) {
            $data['friends'] = $person->friendships->map(function ($friendship) {
                return [
                    'id' => $friendship->friend->id,
                    'first_name' => $friendship->friend->first_name,
                    'last_name' => $friendship->friend->last_name,
                    'full_name' => $friendship->friend->full_name,
                    'gender' => $friendship->friend->gender,
                    'birth_date' => $friendship->friend->birth_date?->format('Y-m-d'),
                    'death_date' => $friendship->friend->death_date?->format('Y-m-d'),
                    'photo_url' => $friendship->friend->photo_url,
                    'description' => $friendship->description,
                    'friendship_story' => $friendship->friendship_story,
                ];
            });
            $data['friendships_count'] = $person->friendships_count ?? $person->friendships->count();
        }

        return $data;
    }

    /**
     * API لجلب عدد الأصدقاء لشخص معين
     */
    public function getFriendshipsCount($id)
    {
        $count = \App\Models\Friendship::where('person_id', $id)->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * API لجلب قائمة الأجيال مع عدد الأشخاص في كل جيل
     */
    public function getGenerations()
    {
        // حساب أعلى مستوى في الشجرة
        $maxDepth = $this->getMaxDepth();

        $generations = [];
        for ($level = 0; $level <= $maxDepth; $level++) {
            $count = $this->countPersonsByLevel($level);
            if ($count > 0) {
                $generations[] = [
                    'level' => $level,
                    'label' => $this->getGenerationLabel($level),
                    'count' => $count
                ];
            }
        }

        return response()->json([
            'success' => true,
            'generations' => $generations
        ]);
    }

    /**
     * حساب أعمق مستوى في الشجرة
     */
    private function getMaxDepth($person = null, $currentDepth = 0)
    {
        $maxDepth = $currentDepth;

        $children = $person
            ? ($person->gender === 'male'
                ? Person::where('parent_id', $person->id)->where('from_outside_the_family', false)->get()
                : Person::where('mother_id', $person->id)->where('from_outside_the_family', false)->get())
            : Person::whereNull('parent_id')->where('from_outside_the_family', false)->get();

        foreach ($children as $child) {
            $depth = $this->getMaxDepth($child, $currentDepth + 1);
            if ($depth > $maxDepth) {
                $maxDepth = $depth;
            }
        }

        return $maxDepth;
    }

    /**
     * حساب عدد الأشخاص في جيل معين
     */
    private function countPersonsByLevel($targetLevel, $person = null, $currentLevel = 0)
    {
        if ($currentLevel > $targetLevel) {
            return 0;
        }

        if ($currentLevel === $targetLevel) {
            return 1;
        }

        $count = 0;
        $children = $person
            ? ($person->gender === 'male'
                ? Person::where('parent_id', $person->id)->where('from_outside_the_family', false)->get()
                : Person::where('mother_id', $person->id)->where('from_outside_the_family', false)->get())
            : Person::whereNull('parent_id')->where('from_outside_the_family', false)->get();

        foreach ($children as $child) {
            $count += $this->countPersonsByLevel($targetLevel, $child, $currentLevel + 1);
        }

        return $count;
    }

    /**
     * الحصول على تسمية الجيل
     */
    private function getGenerationLabel($level)
    {
        $labels = [
            0 => 'الجيل الأول (الأجداد)',
            1 => 'الجيل الثاني',
            2 => 'الجيل الثالث',
            3 => 'الجيل الرابع',
            4 => 'الجيل الخامس',
            5 => 'الجيل السادس',
        ];

        return $labels[$level] ?? "الجيل " . ($level + 1);
    }

    /**
     * API لجلب الأشخاص في جيل معين مع أرقام WhatsApp
     */
    public function getGenerationWithWhatsApp($level)
    {
        $persons = $this->getPersonsByLevel($level);

        // تحميل contactAccounts و parent (لحساب full_name) لكل شخص
        $personIds = $persons->pluck('id');
        $personsWithAccounts = Person::whereIn('id', $personIds)
            ->with([
                'contactAccounts',
                'parent.parent.parent.parent.parent' // تحميل سلسلة الأباء لحساب full_name
            ])
            ->get()
            ->keyBy('id');

        $personsWithWhatsApp = $persons->map(function($person) use ($personsWithAccounts) {
            $personWithAccount = $personsWithAccounts->get($person->id);
            if ($personWithAccount) {
                return $this->formatPersonWithWhatsApp($personWithAccount);
            }
            // إذا لم يتم العثور عليه، نحمل البيانات المطلوبة
            $person->load(['contactAccounts', 'parent.parent.parent.parent.parent']);
            return $this->formatPersonWithWhatsApp($person);
        })->filter(function($person) {
            return !empty($person['whatsapp_numbers']);
        })->values();

        return response()->json([
            'success' => true,
            'persons' => $personsWithWhatsApp
        ]);
    }

    /**
     * جلب الأشخاص في جيل معين
     */
    private function getPersonsByLevel($targetLevel, $person = null, $currentLevel = 0)
    {
        $result = collect();

        if ($currentLevel === $targetLevel) {
            if ($person) {
                $result->push($person);
            } else {
                $result = Person::whereNull('parent_id')
                    ->where('from_outside_the_family', false)
                    ->get();
            }
            return $result;
        }

        $persons = $person
            ? ($person->gender === 'male'
                ? Person::where('parent_id', $person->id)->where('from_outside_the_family', false)->get()
                : Person::where('mother_id', $person->id)->where('from_outside_the_family', false)->get())
            : Person::whereNull('parent_id')
                ->where('from_outside_the_family', false)
                ->get();

        foreach ($persons as $p) {
            $result = $result->merge($this->getPersonsByLevel($targetLevel, $p, $currentLevel + 1));
        }

        return $result;
    }

    /**
     * API لجلب قائمة الأشخاص للبحث (جميع الأشخاص)
     */
    public function searchPersons()
    {
        $persons = Person::select(['id', 'first_name', 'last_name', 'gender', 'parent_id'])
            ->where('from_outside_the_family', false)
            ->with([
                'parent.parent.parent.parent.parent' // تحميل سلسلة الأباء لحساب full_name
            ])
            ->orderBy('first_name')
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'first_name' => $person->first_name,
                    'last_name' => $person->last_name,
                    'full_name' => $person->full_name, // سيتم حسابه تلقائياً بعد تحميل parent
                ];
            });

        return response()->json([
            'success' => true,
            'persons' => $persons
        ]);
    }

    /**
     * API لجلب قائمة الأشخاص الذين لديهم WhatsApp فقط
     */
    public function searchPersonsWithWhatsApp()
    {
        // جلب الأشخاص الذين لديهم WhatsApp في حسابات التواصل
        $persons = Person::select(['persons.id', 'persons.first_name', 'persons.last_name', 'persons.gender', 'persons.parent_id'])
            ->join('person_contact_accounts', 'persons.id', '=', 'person_contact_accounts.person_id')
            ->where('person_contact_accounts.type', 'whatsapp')
            ->where('persons.from_outside_the_family', false)
            ->with([
                'parent.parent.parent.parent.parent' // تحميل سلسلة الأباء لحساب full_name
            ])
            ->distinct()
            ->orderBy('persons.first_name')
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'first_name' => $person->first_name,
                    'last_name' => $person->last_name,
                    'full_name' => $person->full_name, // سيتم حسابه تلقائياً بعد تحميل parent
                ];
            });

        return response()->json([
            'success' => true,
            'persons' => $persons
        ]);
    }

    /**
     * API لجلب شخص معين مع أرقام WhatsApp
     */
    public function getPersonWithWhatsApp($id)
    {
        $person = Person::with([
            'contactAccounts',
            'parent.parent.parent.parent.parent' // تحميل سلسلة الأباء لحساب full_name
        ])->findOrFail($id);
        $personData = $this->formatPersonWithWhatsApp($person);

        return response()->json([
            'success' => true,
            'person' => $personData
        ]);
    }

    /**
     * API لجلب أبناء شخص معين مع أرقام WhatsApp
     */
    public function getChildrenWithWhatsApp($id)
    {
        $person = Person::findOrFail($id);

        $childrenQuery = Person::with([
            'contactAccounts',
            'parent.parent.parent.parent.parent' // تحميل سلسلة الأباء لحساب full_name
        ]);

        if ($person->gender === 'female') {
            $childrenQuery->where('mother_id', $person->id);
        } else {
            $childrenQuery->where('parent_id', $person->id);
        }

        $children = $childrenQuery->get();

        $childrenWithWhatsApp = $children->map(function($child) {
            return $this->formatPersonWithWhatsApp($child);
        })->values();

        return response()->json([
            'success' => true,
            'persons' => $childrenWithWhatsApp
        ]);
    }

    /**
     * API لجلب أحفاد شخص معين مع أرقام WhatsApp
     */
    public function getDescendantsWithWhatsApp($id)
    {
        $person = Person::findOrFail($id);
        $descendants = $this->getAllDescendants($person);

        // تحميل contactAccounts و parent لكل الأحفاد
        $descendantIds = $descendants->pluck('id');
        $descendantsWithData = Person::whereIn('id', $descendantIds)
            ->with([
                'contactAccounts',
                'parent.parent.parent.parent.parent' // تحميل سلسلة الأباء لحساب full_name
            ])
            ->get()
            ->keyBy('id');

        $descendantsWithWhatsApp = $descendants->map(function($descendant) use ($descendantsWithData) {
            $descendantWithData = $descendantsWithData->get($descendant->id);
            if ($descendantWithData) {
                return $this->formatPersonWithWhatsApp($descendantWithData);
            }
            // إذا لم يتم العثور عليه، نحمل البيانات المطلوبة
            $descendant->load(['contactAccounts', 'parent.parent.parent.parent.parent']);
            return $this->formatPersonWithWhatsApp($descendant);
        })->values();

        return response()->json([
            'success' => true,
            'persons' => $descendantsWithWhatsApp
        ]);
    }

    /**
     * جلب جميع الأحفاد بشكل متكرر
     */
    private function getAllDescendants($person)
    {
        $descendants = collect();

        // جلب الأبناء المباشرين (بدون تحميل علاقات هنا، سيتم تحميلها لاحقاً)
        $childrenQuery = Person::where('from_outside_the_family', false);

        if ($person->gender === 'female') {
            $children = $childrenQuery->where('mother_id', $person->id)->get();
        } else {
            $children = $childrenQuery->where('parent_id', $person->id)->get();
        }

        foreach ($children as $child) {
            $descendants->push($child);
            // جلب أحفاد كل ابن بشكل متكرر
            $descendants = $descendants->merge($this->getAllDescendants($child));
        }

        return $descendants;
    }

    /**
     * تنسيق بيانات الشخص مع أرقام WhatsApp
     */
    private function formatPersonWithWhatsApp(Person $person)
    {
        $whatsappNumbers = [];

        // تحميل contactAccounts إذا لم تكن محملة
        if (!$person->relationLoaded('contactAccounts')) {
            $person->load('contactAccounts');
        }

        // تحميل علاقة parent بشكل متكرر لحساب full_name بشكل صحيح
        // نحتاج تحميل parent حتى 5 مستويات لحساب full_name الكامل
        if (!$person->relationLoaded('parent')) {
            $person->load([
                'parent',
                'parent.parent',
                'parent.parent.parent',
                'parent.parent.parent.parent',
                'parent.parent.parent.parent.parent'
            ]);
        } else {
            // إذا كانت parent محملة، نتأكد من تحميل السلسلة الكاملة
            $currentParent = $person->parent;
            $level = 0;
            while ($currentParent && $level < 5) {
                if (!$currentParent->relationLoaded('parent') && $currentParent->parent_id) {
                    $currentParent->load('parent');
                }
                $currentParent = $currentParent->parent;
                $level++;
            }
        }

        if ($person->contactAccounts) {
            $whatsappAccounts = $person->contactAccounts->where('type', 'whatsapp');
            foreach ($whatsappAccounts as $account) {
                // تنظيف الرقم من أي أحرف غير رقمية
                $cleanNumber = preg_replace('/[^0-9]/', '', $account->value);
                if ($cleanNumber && !in_array($cleanNumber, $whatsappNumbers)) {
                    $whatsappNumbers[] = $cleanNumber;
                }
            }
        }

        // حساب full_name بعد تحميل علاقة parent
        $fullName = $person->full_name;

        // التحقق من أن full_name مختلف عن first_name فقط (للتحقق من أن parent تم تحميله)
        if ($fullName === $person->first_name && $person->parent_id) {
            // إذا كان full_name يساوي first_name فقط مع وجود parent_id، يعني أن parent لم يتم تحميله بشكل صحيح
            \Log::warning("Person {$person->id} full_name equals first_name but has parent_id. Parent may not be loaded correctly.");
        }

        return [
            'id' => $person->id,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name,
            'full_name' => $fullName,
            'gender' => $person->gender,
            'photo_url' => $person->avatar,
            'whatsapp_numbers' => $whatsappNumbers
        ];
    }
}
