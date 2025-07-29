<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Marriage;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MarriageController extends Controller
{
    public function index(Request $request)
    {
        $query = Marriage::with(['husband', 'wife'])
            ->select('marriages.*')
            ->join('persons as husband', 'husband.id', '=', 'marriages.husband_id')
            ->join('persons as wife', 'wife.id', '=', 'marriages.wife_id');

        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();

        // التصفية حسب الحالة
        $status = $request->input('status', 'all');

        if ($status === 'active') {
            $query->whereNotNull('married_at')
                ->whereNull('divorced_at');
        } elseif ($status === 'divorced') {
            $query->whereNotNull('divorced_at');
        } elseif ($status === 'unknown') {
            $query->whereNull('married_at');
        }

        // البحث حسب الزوج
        if ($request->has('husband_id')) {
            $query->where('husband_id', $request->husband_id);
        }

        // البحث حسب الزوجة
        if ($request->has('wife_id')) {
            $query->where('wife_id', $request->wife_id);
        }

        // البحث حسب نطاق التاريخ
        if ($request->has('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();

                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('married_at', [$startDate, $endDate])
                        ->orWhereBetween('divorced_at', [$startDate, $endDate]);
                });
            }
        }

        // الترتيب
        $query->orderBy('married_at', 'desc');

        $marriages = $query->paginate(15);

        // الإحصائيات
        $totalMarriages = Marriage::count();
        $activeMarriages = Marriage::whereNotNull('married_at')->whereNull('divorced_at')->count();
        $divorcedMarriages = Marriage::whereNotNull('divorced_at')->count();
        $unknownStatusMarriages = Marriage::whereNull('married_at')->count();

        // قائمة الأشخاص للبحث
        $persons = Person::orderBy('first_name')->get();

        return view('dashboard.marriages.index', compact(
            'marriages',
            'status',
            'totalMarriages',
            'activeMarriages',
            'divorcedMarriages',
            'unknownStatusMarriages',
            'persons',
            'males',
            'females'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'husband_id' => 'required|exists:persons,id',
            'wife_id' => 'required|exists:persons,id|different:husband_id',
            'married_at' => 'nullable|date',
            'divorced_at' => 'nullable|date|after_or_equal:married_at',
        ]);

        // =================== بداية منطق التحقق الجديد ===================

        // 1. التحقق مما إذا كانت الزوجة لديها زواج حالي نشط
        $activeMarriage = Marriage::where('wife_id', $request->wife_id)
            ->whereNull('divorced_at') // الزواج لم ينتهِ بالطلاق
            ->first();

        if ($activeMarriage) {
            // 2. إذا كان هناك زواج نشط، تحقق من حالة الزوج السابق
            $previousHusband = Person::find($activeMarriage->husband_id);

            // إذا كان الزوج السابق موجودًا وعلى قيد الحياة (ليس له تاريخ وفاة)
            if ($previousHusband && is_null($previousHusband->death_date)) {
                return redirect()->back()
                    ->withErrors(['error' => 'لا يمكن إتمام الزواج. الزوجة مرتبطة بزواج آخر لم ينتهِ بعد (بالطلاق أو بوفاة الزوج).'])
                    ->withInput();
            }
        }

        // =================== نهاية منطق التحقق الجديد ===================


        // التحقق من عدم وجود سجل زواج مكرر لنفس الشخصين
        $existingMarriage = Marriage::where('husband_id', $request->husband_id)
            ->where('wife_id', $request->wife_id)
            ->exists();

        if ($existingMarriage) {
            return redirect()->back()->withErrors(['error' => 'هذا الزواج مسجل بالفعل!'])->withInput();
        }

        DB::beginTransaction();
        try {
            $marriage = Marriage::create($request->all());

            DB::commit();
            return redirect()->route('marriages.index')->with('success', 'تم إضافة سجل الزواج بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()])->withInput();
        }
    }
    public function show(Marriage $marriage)
    {
        return view('dashboard.marriages.show', compact('marriage'));
    }

    public function update(Request $request, Marriage $marriage)
    {
        $request->validate([
            'husband_id' => 'required|exists:persons,id',
            'wife_id' => 'required|exists:persons,id|different:husband_id',
            'married_at' => 'nullable|date',
            'divorced_at' => 'nullable|date|after_or_equal:married_at',
        ]);

        // التحقق من عدم وجود زواج سابق بين نفس الشخصين (باستثناء السجل الحالي)
        $existingMarriage = Marriage::where('id', '!=', $marriage->id)
            ->where(function ($query) use ($request) {
                $query->where('husband_id', $request->husband_id)
                    ->where('wife_id', $request->wife_id);
            })->orWhere(function ($query) use ($request) {
                $query->where('husband_id', $request->wife_id)
                    ->where('wife_id', $request->husband_id);
            })->exists();

        if ($existingMarriage) {
            return redirect()->back()->withErrors(['error' => 'هذا الزواج مسجل بالفعل!'])->withInput();
        }

        DB::beginTransaction();
        try {
            $marriage->update($request->all());

            DB::commit();
            return redirect()->route('marriages.index')->with('success', 'تم تحديث سجل الزواج بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Marriage $marriage)
    {
        DB::beginTransaction();
        try {
            $marriage->delete();

            DB::commit();
            return redirect()->route('marriages.index')->with('success', 'تم حذف سجل الزواج بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف البيانات: ' . $e->getMessage()]);
        }
    }
}
