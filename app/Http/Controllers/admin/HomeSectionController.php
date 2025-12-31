<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeSectionController extends Controller
{
    /**
     * عرض صفحة إدارة الأقسام الديناميكية
     */
    public function index()
    {
        $sections = HomeSection::orderBy('display_order')->with('items')->get();
        
        $stats = [
            'total' => HomeSection::count(),
            'active' => HomeSection::where('is_active', true)->count(),
            'inactive' => HomeSection::where('is_active', false)->count(),
        ];
        
        return view('dashboard.home-sections.index', compact('sections', 'stats'));
    }

    /**
     * عرض نموذج إنشاء قسم جديد
     */
    public function create()
    {
        return view('dashboard.home-sections.create');
    }

    /**
     * حفظ قسم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_type' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'settings' => 'nullable|array',
            'css_classes' => 'nullable|string|max:500',
        ]);

        $lastOrder = HomeSection::max('display_order') ?? 0;

        HomeSection::create([
            'title' => $request->title,
            'section_type' => $request->section_type,
            'display_order' => $lastOrder + 1,
            'is_active' => $request->has('is_active') ? true : false,
            'settings' => $request->settings ?? [],
            'css_classes' => $request->css_classes,
        ]);

        return redirect()->route('dashboard.home-sections.index')
            ->with('success', 'تم إنشاء القسم بنجاح');
    }

    /**
     * عرض نموذج تعديل قسم
     */
    public function edit(HomeSection $homeSection)
    {
        $homeSection->load('items');
        return view('dashboard.home-sections.edit', compact('homeSection'));
    }

    /**
     * تحديث قسم
     */
    public function update(Request $request, HomeSection $homeSection)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_type' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'settings' => 'nullable|array',
            'css_classes' => 'nullable|string|max:500',
        ]);

        $homeSection->update([
            'title' => $request->title,
            'section_type' => $request->section_type,
            'is_active' => $request->has('is_active') ? true : false,
            'settings' => $request->settings ?? [],
            'css_classes' => $request->css_classes,
        ]);

        return redirect()->route('dashboard.home-sections.index')
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    /**
     * حذف قسم
     */
    public function destroy(HomeSection $homeSection)
    {
        // حذف جميع العناصر المرتبطة
        foreach ($homeSection->items as $item) {
            if ($item->media_path && !filter_var($item->media_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($item->media_path);
            }
        }
        
        $homeSection->delete();

        return redirect()->route('dashboard.home-sections.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }

    /**
     * إعادة ترتيب الأقسام
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:home_sections,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $sectionId) {
                HomeSection::where('id', $sectionId)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * تفعيل/تعطيل قسم
     */
    public function toggle(HomeSection $homeSection)
    {
        $homeSection->toggle();

        return redirect()->route('dashboard.home-sections.index')
            ->with('success', 'تم تحديث حالة القسم بنجاح');
    }
}
