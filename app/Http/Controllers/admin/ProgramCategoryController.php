<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramCategory;
use Illuminate\Http\Request;

class ProgramCategoryController extends Controller
{
    public function index()
    {
        $categories = ProgramCategory::query()
            ->withCount('programs')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $stats = [
            'total' => ProgramCategory::count(),
            'active' => ProgramCategory::where('is_active', true)->count(),
        ];

        return view('dashboard.program-categories.index', compact('categories', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ProgramCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->filled('sort_order') ? (int) $request->sort_order : null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.program-categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح');
    }

    public function update(Request $request, ProgramCategory $programCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $programCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->filled('sort_order') ? (int) $request->sort_order : $programCategory->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.program-categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(ProgramCategory $programCategory)
    {
        $programCategory->delete();

        return redirect()->route('dashboard.program-categories.index')
            ->with('success', 'تم حذف الفئة. البرامج المرتبطة أصبحت ضمن «بدون تصنيف».');
    }

    public function toggle(ProgramCategory $programCategory)
    {
        $programCategory->update(['is_active' => ! $programCategory->is_active]);

        return redirect()->route('dashboard.program-categories.index')
            ->with('success', 'تم تحديث حالة الفئة');
    }
}
