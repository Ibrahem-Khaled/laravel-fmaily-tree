<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Padge;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PadgePeopleController extends Controller
{
    // صفحة إدارة الأشخاص لشارة محددة
    public function index(Request $request, Padge $padge)
    {
        // أشخاص الشارة الحاليون
        $people = $padge->people()
            ->select('persons.*') // لتفادي أي غموض في select
            ->paginate(10);

        // IDs المرتبطين، لا نريد عرضهم في قائمة الإضافة
        $attachedIds = $padge->people()->pluck('persons.id')->all();

        // فلترة اختيارية بالاسم/الإيميل لقائمة "المتاحين" (سيرفر سايد، بدون JS)
        $q = $request->input('q');

        $availableQuery = Person::query()
            ->when($q, function ($sub) use ($q) {
                $sub->where(function ($x) use ($q) {
                    $x->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%");
                });
            })
            ->when(!empty($attachedIds), fn($sub) => $sub->whereNotIn('id', $attachedIds));

        $availablePeople = $availableQuery->paginate(20)->withQueryString();

        return view('dashboard.padges.people.index', compact('padge', 'people', 'availablePeople', 'q'));
    }

    // إرفاق شخص/أشخاص
    public function attach(Request $request, Padge $padge)
    {
        $data = $request->validate([
            'people'   => ['required', 'array', 'min:1'],
            'people.*' => ['integer', Rule::exists('persons', 'id')], // << تم التصحيح
        ]);

        // بناء مصفوفة إرفاق مع قيم pivot إضافية (is_active=true افتراضياً)
        // ملاحظة: تمرير قيم pivot يتم كمصفوفة [id => ['col' => val], ...]
        // حسب توثيق Laravel. :contentReference[oaicite:2]{index=2}
        $attachPayload = collect($data['people'])
            ->mapWithKeys(fn($id) => [$id => ['is_active' => true]])
            ->toArray();

        // syncWithoutDetaching يضيف الجديد ولا يفصل الموجود
        $padge->people()->syncWithoutDetaching($attachPayload);

        return redirect()
            ->route('padges.people.index', $padge)
            ->with('success', 'تم ربط الأشخاص بالشارة بنجاح.');
    }

    // فصل شخص
    public function detach(Padge $padge, Person $person)
    {
        $padge->people()->detach($person->id);

        return redirect()
            ->route('padges.people.index', $padge)
            ->with('success', 'تم إزالة الشخص من الشارة.');
    }

    // تبديل تفعيل/تعطيل العلاقة على البيفوت
    public function toggle(Padge $padge, Person $person)
    {
        $current = $padge->people()->where('persons.id', $person->id)->firstOrFail();
        $new = ! (bool) $current->pivot->is_active;

        $padge->people()->updateExistingPivot($person->id, ['is_active' => $new]);

        return redirect()
            ->route('padges.people.index', $padge)
            ->with('success', 'تم تحديث حالة الارتباط.');
    }
}
