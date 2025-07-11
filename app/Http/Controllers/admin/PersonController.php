<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class PersonController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gender = $request->input('gender');

        // 1. الاستعلام الأساسي لجلب الأشخاص مع الفلاتر والبحث
        // هذا الجزء سيبقى كما هو لأنه فعال ويستخدم الترحيل (pagination)
        $people = Person::when($search, function ($query, $search) {
            // تحسين بسيط: تجميع شروط الـ orWhere لتجنب أي تعارض مستقبلي
            return $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        })
            ->when($gender, function ($query, $gender) {
                return $query->where('gender', $gender);
            })
            ->defaultOrder() // افترض أن هذا scope موجود في الموديل
            ->paginate(10);
        $males = Person::where('gender', 'male')->get(['id', 'first_name', 'last_name']);
        $females = Person::where('gender', 'female')->get(['id', 'first_name', 'last_name']);
        // 2. حساب الإحصائيات بكفاءة باستخدام الكاش والاستعلام المجمع
        // سيتم تخزين النتيجة لمدة 60 دقيقة لتجنب إعادة حسابها مع كل طلب
        $stats = Cache::remember('people_stats', now()->addMinutes(60), function () {
            // استخدام استعلام واحد فقط لجلب كل الإحصائيات
            $result = DB::table('persons')
                ->selectRaw("
                    COUNT(*) as total,
                    COUNT(CASE WHEN gender = 'male' THEN 1 END) as male,
                    COUNT(CASE WHEN gender = 'female' THEN 1 END) as female,
                    COUNT(CASE WHEN death_date IS NULL THEN 1 END) as living,
                    COUNT(CASE WHEN photo_url IS NOT NULL THEN 1 END) as with_photos
                ")
                ->first();

            // تحويل النتيجة من stdClass إلى array
            return (array) $result;
        });

        // 3. إرسال البيانات إلى الـ view
        return view('people.index', compact('people', 'stats', 'males', 'females'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|max:2048',
            'biography' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:persons,id',
            'mother_id' => 'nullable|exists:persons,id', // إذا كنت تريد استخدام هذا الحقل
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_url'] = $request->file('photo')->store('people-photos', 'public');
        }

        // ✨ فصل الإنشاء عن ترتيب الشجرة
        $person = new Person($validated);
        $person->save();

        if (!empty($validated['parent_id'])) {
            $parent = Person::find($validated['parent_id']);
            $person->appendToNode($parent)->save();
        }

        return redirect()->route('people.index')->with('success', 'تمت إضافة الشخص بنجاح');
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|max:2048',
            'biography' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:persons,id',
            'mother_id' => 'nullable|exists:persons,id', // إذا كنت تريد استخدام هذا الحقل
        ]);

        if ($request->hasFile('photo')) {
            if ($person->photo_url) {
                Storage::disk('public')->delete($person->photo_url);
            }
            $validated['photo_url'] = $request->file('photo')->store('people-photos', 'public');
        }

        $person->update($validated);

        if (!empty($validated['parent_id'])) {
            $parent = Person::find($validated['parent_id']);
            $person->appendToNode($parent)->save();
        }

        return redirect()->route('people.index')->with('success', 'تم تحديث بيانات الشخص بنجاح');
    }

    public function destroy(Person $person)
    {
        if ($person->photo_url) {
            Storage::disk('public')->delete($person->photo_url);
        }

        $person->delete();
        return redirect()->route('people.index')->with('success', 'تم حذف الشخص بنجاح');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('term', '');

        // نقوم بالبحث فقط إذا أدخل المستخدم حرفين على الأقل
        if (strlen($searchTerm) < 2) {
            return response()->json(['results' => []]);
        }

        // بناء الاستعلام للبحث في الاسم الأول والأخير
        // استخدام CONCAT لجعل البحث أكثر كفاءة في قاعدة البيانات
        $people = Person::query()
            ->select(
                'id',
                DB::raw("CONCAT(first_name, ' ', last_name) as text") // تجهيز النص للعرض في Select2
            )
            ->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
            })
            ->limit(20) // نحدد عدد النتائج لتجنب إغراق المتصفح
            ->get();

        // إرجاع النتائج بالصيغة المطلوبة لمكتبة Select2
        return response()->json(['results' => $people]);
    }
}
