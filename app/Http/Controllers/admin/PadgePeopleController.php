<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Padge;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PadgePeopleController extends Controller
{
    // نفس الـ index (عرض الصفحة القديمة) بدون أي صفحة جديدة
    public function index(Request $request, Padge $padge)
    {
        $q = $request->string('q')->toString();

        $selected = collect($request->input('people', []))
            ->map(fn($id) => (int) $id)->filter()->values()->all();

        $availablePeople = Person::query()
            ->when($q, fn($qq) => $qq->where(
                fn($w) =>
                $w->where('first_name', 'like', "%$q%")
                    ->orWhere('last_name', 'like', "%$q%")
                    ->orWhere('biography', 'like', "%$q%")
            ))
            // استبعاد المرتبطين فعلاً بهذه الشارة
            ->whereDoesntHave('padges', fn($r) => $r->where('padge_id', $padge->id))
            ->paginate(12);

        $people = $padge->people()->paginate(10);

        return view('dashboard.padges.people.index', compact(
            'padge',
            'q',
            'availablePeople',
            'people',
            'selected'
        ));
    }

    // بحث AJAX يرجع JSON
    public function search(Request $request, Padge $padge)
    {
        $q = trim((string) $request->query('q', ''));
        $page = max(1, (int) $request->query('page', 1));
        $perPage = min(30, max(5, (int) $request->query('per_page', 12)));

        $exclude = collect($request->query('exclude', []))
            ->map(fn($id) => (int) $id)->filter()->values()->all();

        $builder = Person::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('first_name', 'like', "%$q%")
                        ->orWhere('last_name', 'like', "%$q%")
                        ->orWhere('biography', 'like', "%$q%");
                });
            })
            ->whereDoesntHave('padges', fn($r) => $r->where('padge_id', $padge->id))
            ->when(!empty($exclude), fn($qq) => $qq->whereNotIn('id', $exclude));

        $paginator = $builder->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->getCollection()->map(fn($p) => [
                'id'        => $p->id,
                'full_name' => $p->full_name,
                'email'     => $p->email,
            ])->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    // إرفاق أشخاص (يدعم AJAX و Redirect)
    public function attach(Request $request, Padge $padge)
    {
        $peopleTable = (new Person)->getTable(); // يرجّع 'persons' عندك
        $data = $request->validate([
            'people'   => ['required', 'array', 'min:1'],
            'people.*' => ['integer', Rule::exists($peopleTable, 'id')],
        ]);

        $payload = collect($data['people'])->unique()->mapWithKeys(
            fn($id) => [(int)$id => ['is_active' => true]]
        )->toArray();

        $padge->people()->syncWithoutDetaching($payload); // لا يفصل القدام ويضيف الجدد

        if ($request->wantsJson()) {
            // رجّع قائمة المرتبطين الحالية بعد الإضافة (مصغرة)
            $linked = $padge->people()->get(["$peopleTable.id", "$peopleTable.first_name", "$peopleTable.last_name"])
                ->map(fn($p) => [
                    'id'        => $p->id,
                    'full_name' => $p->full_name,
                ])->values();
            return response()->json([
                'ok' => true,
                'linked' => $linked,
                'message' => 'تمت إضافة الأشخاص المحددين.',
            ]);
        }

        return redirect()->route('padges.people.index', $padge)->with('success', 'تمت إضافة الأشخاص المحددين.');
    }

    // فصل شخص (AJAX + Redirect)
    public function detach(Request $request, Padge $padge, Person $person)
    {
        $padge->people()->detach($person->id);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'تم إزالة الشخص من الشارة.']);
        }

        return redirect()->route('padges.people.index', $padge)->with('success', 'تم إزالة الشخص من الشارة.');
    }

    // تبديل is_active (AJAX + Redirect)
    public function toggle(Request $request, Padge $padge, Person $person)
    {
        $current = $padge->people()->where('people.id', $person->id)->firstOrFail();
        $new = ! (bool) $current->pivot->is_active;

        $padge->people()->updateExistingPivot($person->id, ['is_active' => $new]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'is_active' => $new, 'message' => 'تم تحديث حالة الارتباط.']);
        }

        return redirect()->route('padges.people.index', $padge)->with('success', 'تم تحديث حالة الارتباط.');
    }
}
