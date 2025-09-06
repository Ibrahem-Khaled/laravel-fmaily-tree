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
        $search = trim((string) $request->input('search'));
        $gender = $request->input('gender');

        $people = Person::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        // الأب المباشر
                        ->orWhereHas('parent', function ($p) use ($search) {
                            $p->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        // جدّ الأب (اختياري إن كانت العلاقة متوفرة):
                        ->orWhereHas('parent.parent', function ($gp) use ($search) {
                            $gp->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($gender, fn($q) => $q->where('gender', $gender))
            ->paginate(10)
            ->withQueryString();

        // لو جدولك الحقيقي اسمه "persons" فثبّت اسم الجدول في الموديل:
        // protected $table = 'persons';

        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();

        $stats = Cache::remember('people_stats', now()->addMinutes(60), function () {
            return (array) DB::table('persons')->selectRaw("
            COUNT(*) as total,
            COUNT(CASE WHEN gender = 'male' THEN 1 END) as male,
            COUNT(CASE WHEN gender = 'female' THEN 1 END) as female,
            COUNT(CASE WHEN death_date IS NULL THEN 1 END) as living,
            COUNT(CASE WHEN photo_url IS NOT NULL THEN 1 END) as with_photos
        ")->first();
        });

        return view('dashboard.people.index', compact('people', 'stats', 'males', 'females'));
    }

    public function store(Request $request)
    {
        // أضفنا source_page إلى التحقق
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
            'mother_id' => 'nullable|exists:persons,id',
            'source_page' => 'nullable|string', // للتحقق من مصدر الطلب
        ]);

        // تأكد من أن اسم الحقل في قاعدة البيانات هو 'photo'
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('people-photos', 'public');
        }

        // استخدام create لإنشاء الشخص مباشرة
        $person = Person::create($validated);

        // ربط الشخص بوالده إذا تم اختياره
        if (!empty($validated['parent_id'])) {
            $parent = Person::find($validated['parent_id']);
            if ($parent) {
                $person->appendToNode($parent)->save();
            }
        }

        // ✨ التحقق من مصدر الطلب لتحديد وجهة إعادة التوجيه
        if ($request->input('source_page') === 'add_self') {
            // إذا كان الطلب من صفحة "إضافة نفسك"، وجهه إلى صفحة عرض الشخص الجديد
            return redirect()->route('people.show', $person->id)
                ->with('success', 'تمت إضافة بياناتك بنجاح! مرحباً بك في تواصل العائلة.');
        } else {
            // إذا كان الطلب من أي مكان آخر (مثل المودال)، أعده إلى الصفحة السابقة
            return redirect()->back()->with('success', 'تمت إضافة الشخص بنجاح');
        }
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

        return redirect()->back()->with('success', 'تم تحديث بيانات الشخص بنجاح');
    }

    public function destroy(Person $person)
    {
        if ($person->photo_url) {
            Storage::disk('public')->delete($person->photo_url);
        }

        $person->delete();
        return redirect()->back()->with('success', 'تم حذف الشخص بنجاح');
    }

    public function removePhoto(Person $person)
    {
        if ($person->photo_url) {
            Storage::disk('public')->delete($person->photo_url);
            $person->update(['photo_url' => null]);
        }

        return redirect()->back()->with('success', 'تم حذف صورة الشخص بنجاح');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:persons,id',
            'order.*.order' => 'required|integer',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->order as $personData) {
                    Person::where('id', $personData['id'])->update(['display_order' => $personData['order']]);
                }
            });

            return response()->json(['status' => 'success', 'message' => 'تم تحديث الترتيب بنجاح.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'حدث خطأ أثناء تحديث الترتيب.'], 500);
        }
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

    public function show(Person $person)
    {
        // جلب قوائم الذكور والإناث اللازمة لمودال التعديل
        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();

        // جلب الأبناء بناءً على جنس الشخص
        if ($person->gender === 'female') {
            $children = Person::where('mother_id', $person->id)->get();
        } else {
            // علاقة 'children' تفترض البحث بـ parent_id
            $children = $person->children()->get();
        }

        // جلب الزوجات أو الزوج
        $spouses = collect(); // إنشاء collection فارغة
        if ($person->gender === 'male') {
            $spouses = $person->wives;
        } elseif (is_object($person->husband)) {
            $spouses = collect([$person->husband]);
        }

        // تمرير كل البيانات اللازمة إلى الـ view
        return view('dashboard.people.show', [
            'person'   => $person,
            'children' => $children,
            'spouses'  => $spouses,
            'males'    => $males,    // <-- إضافة قائمة الذكور
            'females'  => $females,  // <-- إضافة قائمة الإناث
        ]);
    }


    public function getWives(Person $father)
    {
        // استخدام العلاقة 'wives' الموجودة في موديل Person لجلب الزوجات
        $wives = $father->wives()->get();

        // إرجاع قائمة الزوجات كـ JSON
        return response()->json($wives);
    }
}
