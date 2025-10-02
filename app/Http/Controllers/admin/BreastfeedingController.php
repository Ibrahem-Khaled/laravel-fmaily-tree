<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class BreastfeedingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('dashboard.breastfeeding.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get all females who can be nursing mothers
        $nursingMothers = Person::where('gender', 'female')
            ->orderBy('first_name')
            ->get();

        // Get all persons who can be breastfed children
        $breastfedChildren = Person::orderBy('first_name')->get();

        return view('dashboard.breastfeeding.create', compact('nursingMothers', 'breastfedChildren'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nursing_mother_id' => 'required|exists:persons,id',
            'breastfed_child_id' => 'required|exists:persons,id|different:nursing_mother_id',
            'start_date' => 'nullable|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ], [
            'nursing_mother_id.required' => 'يجب اختيار الأم المرضعة',
            'nursing_mother_id.exists' => 'الأم المرضعة المختارة غير موجودة',
            'breastfed_child_id.required' => 'يجب اختيار الطفل المرتضع',
            'breastfed_child_id.exists' => 'الطفل المرتضع المختار غير موجود',
            'breastfed_child_id.different' => 'لا يمكن أن تكون الأم المرضعة والطفل المرتضع نفس الشخص',
            'start_date.date' => 'تاريخ البداية غير صحيح',
            'start_date.before_or_equal' => 'تاريخ البداية لا يمكن أن يكون في المستقبل',
            'end_date.date' => 'تاريخ النهاية غير صحيح',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية أو مساوياً له',
            'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف',
        ]);

        // Check if relationship already exists
        $existingRelationship = Breastfeeding::where('nursing_mother_id', $validated['nursing_mother_id'])
            ->where('breastfed_child_id', $validated['breastfed_child_id'])
            ->first();

        if ($existingRelationship) {
            return back()->withErrors(['relationship' => 'هذه العلاقة موجودة بالفعل'])->withInput();
        }

        // Ensure nursing mother is female
        $nursingMother = Person::find($validated['nursing_mother_id']);
        if ($nursingMother->gender !== 'female') {
            return back()->withErrors(['nursing_mother_id' => 'الأم المرضعة يجب أن تكون أنثى'])->withInput();
        }

        Breastfeeding::create($validated);

        return redirect()->route('breastfeeding.index')
            ->with('success', 'تم إضافة علاقة الرضاعة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Breastfeeding $breastfeeding): View
    {
        $breastfeeding->load(['nursingMother', 'breastfedChild']);

        return view('dashboard.breastfeeding.show', compact('breastfeeding'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breastfeeding $breastfeeding): View
    {
        $breastfeeding->load(['nursingMother', 'breastfedChild']);

        // Get all females who can be nursing mothers
        $nursingMothers = Person::where('gender', 'female')
            ->orderBy('first_name')
            ->get();

        // Get all persons who can be breastfed children
        $breastfedChildren = Person::orderBy('first_name')->get();

        return view('dashboard.breastfeeding.edit', compact('breastfeeding', 'nursingMothers', 'breastfedChildren'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Breastfeeding $breastfeeding): RedirectResponse
    {
        $validated = $request->validate([
            'nursing_mother_id' => 'required|exists:persons,id',
            'breastfed_child_id' => 'required|exists:persons,id|different:nursing_mother_id',
            'start_date' => 'nullable|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ], [
            'nursing_mother_id.required' => 'يجب اختيار الأم المرضعة',
            'nursing_mother_id.exists' => 'الأم المرضعة المختارة غير موجودة',
            'breastfed_child_id.required' => 'يجب اختيار الطفل المرتضع',
            'breastfed_child_id.exists' => 'الطفل المرتضع المختار غير موجود',
            'breastfed_child_id.different' => 'لا يمكن أن تكون الأم المرضعة والطفل المرتضع نفس الشخص',
            'start_date.date' => 'تاريخ البداية غير صحيح',
            'start_date.before_or_equal' => 'تاريخ البداية لا يمكن أن يكون في المستقبل',
            'end_date.date' => 'تاريخ النهاية غير صحيح',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية أو مساوياً له',
            'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف',
        ]);

        // Check if relationship already exists (excluding current record)
        $existingRelationship = Breastfeeding::where('nursing_mother_id', $validated['nursing_mother_id'])
            ->where('breastfed_child_id', $validated['breastfed_child_id'])
            ->where('id', '!=', $breastfeeding->id)
            ->first();

        if ($existingRelationship) {
            return back()->withErrors(['relationship' => 'هذه العلاقة موجودة بالفعل'])->withInput();
        }

        // Ensure nursing mother is female
        $nursingMother = Person::find($validated['nursing_mother_id']);
        if ($nursingMother->gender !== 'female') {
            return back()->withErrors(['nursing_mother_id' => 'الأم المرضعة يجب أن تكون أنثى'])->withInput();
        }

        $breastfeeding->update($validated);

        return redirect()->route('breastfeeding.index')
            ->with('success', 'تم تحديث علاقة الرضاعة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breastfeeding $breastfeeding): RedirectResponse
    {
        $breastfeeding->delete();

        return redirect()->route('breastfeeding.index')
            ->with('success', 'تم حذف علاقة الرضاعة بنجاح');
    }

    /**
     * Toggle active status of breastfeeding relationship
     */
    public function toggleStatus(Breastfeeding $breastfeeding): RedirectResponse
    {
        $breastfeeding->update(['is_active' => !$breastfeeding->is_active]);

        $status = $breastfeeding->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        return redirect()->route('breastfeeding.index')
            ->with('success', "تم {$status} علاقة الرضاعة بنجاح");
    }

    /**
     * Get nursing mothers for AJAX requests
     */
    public function getNursingMothers(Request $request)
    {
        $query = $request->get('q', '');

        $nursingMothers = Person::where('gender', 'female')
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->get(['id', 'first_name', 'last_name']);

        return response()->json($nursingMothers);
    }

    /**
     * Get breastfed children for AJAX requests
     */
    public function getBreastfedChildren(Request $request)
    {
        $query = $request->get('q', '');

        $children = Person::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->get(['id', 'first_name', 'last_name']);

        return response()->json($children);
    }
}
