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
            'settings.subtitle' => 'nullable|string|max:500',
            'settings.description' => 'nullable|string|max:2000',
            'settings.icon' => 'nullable|string|max:100',
            'settings.background_color' => 'nullable|string|max:50',
            'settings.text_color' => 'nullable|string|max:50',
            'settings.padding_top' => 'nullable|integer|min:0|max:200',
            'settings.padding_bottom' => 'nullable|integer|min:0|max:200',
            'settings.show_title' => 'nullable|boolean',
            'settings.columns' => 'nullable|integer|min:1|max:6',
            'settings.carousel_autoplay' => 'nullable|boolean',
            'settings.carousel_interval' => 'nullable|integer|min:1000|max:30000',
            'settings.layout_style' => 'nullable|string|in:grid,horizontal,vertical',
            'settings.source_limit' => 'nullable|integer|min:1|max:100',
            'settings.source_order' => 'nullable|string|in:latest,oldest,name_asc,name_desc',
            'settings.source_mode' => 'nullable|string|in:all,selected',
            'settings.source_ids' => 'nullable|array',
            'settings.source_ids.*' => 'nullable|integer',
            'css_classes' => 'nullable|string|max:500',
            'content_source_type' => 'nullable|string|max:255',
        ]);

        $lastOrder = HomeSection::max('display_order') ?? 0;

        $settings = $request->settings ?? [];
        $settings['show_title'] = isset($settings['show_title']) ? true : ($request->has('settings.show_title') ? true : true);
        $settings['carousel_autoplay'] = isset($settings['carousel_autoplay']) ? true : false;
        if (isset($settings['source_ids'])) {
            $settings['source_ids'] = array_map('intval', array_filter($settings['source_ids']));
        }

        $contentSource = $request->content_source_type;
        if ($contentSource && !array_key_exists($contentSource, HomeSection::ALLOWED_SOURCES)) {
            $contentSource = null;
        }

        $section = HomeSection::create([
            'title' => $request->title,
            'section_type' => $request->section_type,
            'display_order' => $lastOrder + 1,
            'is_active' => $request->has('is_active') ? true : false,
            'settings' => $settings,
            'css_classes' => $request->css_classes,
            'content_source_type' => $contentSource,
        ]);

        return redirect()->route('dashboard.home-sections.edit', $section)
            ->with('success', 'تم إنشاء القسم بنجاح! يمكنك الآن إضافة العناصر.');
    }

    /**
     * عرض نموذج تعديل قسم (واجهة بناء الصفحات)
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
            'settings.layout_style' => 'nullable|string|in:grid,horizontal,vertical',
            'settings.source_limit' => 'nullable|integer|min:1|max:100',
            'settings.source_order' => 'nullable|string|in:latest,oldest,name_asc,name_desc',
            'settings.source_mode' => 'nullable|string|in:all,selected',
            'settings.source_ids' => 'nullable|array',
            'settings.source_ids.*' => 'nullable|integer',
            'css_classes' => 'nullable|string|max:500',
            'content_source_type' => 'nullable|string|max:255',
        ]);

        $settings = $request->settings ?? [];
        $settings['show_title'] = isset($settings['show_title']) ? true : false;
        $settings['carousel_autoplay'] = isset($settings['carousel_autoplay']) ? true : false;
        if (isset($settings['source_ids'])) {
            $settings['source_ids'] = array_map('intval', array_filter($settings['source_ids']));
        }

        $contentSource = $request->content_source_type;
        if ($contentSource && !array_key_exists($contentSource, HomeSection::ALLOWED_SOURCES)) {
            $contentSource = null;
        }

        $homeSection->update([
            'title' => $request->title,
            'section_type' => $request->section_type,
            'is_active' => $request->has('is_active') ? true : false,
            'settings' => $settings,
            'css_classes' => $request->css_classes,
            'content_source_type' => $contentSource ?: null,
        ]);

        return redirect()->route('dashboard.home-sections.edit', $homeSection)
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

    /**
     * بحث AJAX في مصادر المحتوى (مستخدمين / أشخاص)
     */
    public function searchSource(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'q' => 'nullable|string|max:255',
        ]);

        $type = $request->type;
        if (!array_key_exists($type, HomeSection::ALLOWED_SOURCES)) {
            return response()->json([]);
        }

        $q = $request->q ?? '';
        $query = $type::query();

        if ($type === 'App\\Models\\Person') {
            if ($q) {
                $query->where(function ($qb) use ($q) {
                    $qb->where('first_name', 'like', "%{$q}%")
                       ->orWhere('last_name', 'like', "%{$q}%");
                });
            }
            $results = $query->limit(30)->get()->map(function ($p) {
                return [
                    'id' => $p->id,
                    'text' => $p->full_name,
                    'avatar' => $p->avatar,
                ];
            });
        } else {
            if ($q) {
                $query->where(function ($qb) use ($q) {
                    $qb->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            }
            $results = $query->limit(30)->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'text' => $u->name . ($u->email ? " ({$u->email})" : ''),
                    'avatar' => $u->avatar ? asset('storage/' . $u->avatar) : null,
                ];
            });
        }

        return response()->json($results->values());
    }

    /**
     * نسخ قسم
     */
    public function duplicate(HomeSection $homeSection)
    {
        DB::transaction(function () use ($homeSection) {
            $lastOrder = HomeSection::max('display_order') ?? 0;
            
            $newSection = $homeSection->replicate();
            $newSection->title = $homeSection->title . ' (نسخة)';
            $newSection->display_order = $lastOrder + 1;
            $newSection->is_active = false;
            $newSection->save();

            foreach ($homeSection->items as $item) {
                $newItem = $item->replicate();
                $newItem->home_section_id = $newSection->id;
                $newItem->save();
            }
        });

        return redirect()->route('dashboard.home-sections.index')
            ->with('success', 'تم نسخ القسم بنجاح');
    }
}
