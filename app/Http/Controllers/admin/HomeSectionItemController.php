<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeSectionItemController extends Controller
{
    /**
     * إضافة عنصر لقسم
     */
    public function store(Request $request, HomeSection $homeSection)
    {
        $request->validate([
            'item_type' => 'required|string|max:255',
            'content' => 'nullable|array',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg,mp4,avi,mov|max:20480',
            'youtube_url' => 'nullable|url|max:500',
            'settings' => 'nullable|array',
        ]);

        $lastOrder = HomeSectionItem::where('home_section_id', $homeSection->id)
            ->max('display_order') ?? 0;

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $folder = in_array($request->item_type, ['video', 'video_section']) ? 'home-sections/videos' : 'home-sections/images';
            $mediaPath = $request->file('media')->store($folder, 'public');
        }

        // تنظيف المحتوى حسب النوع
        $content = $request->content ?? [];
        
        // للنصوص الغنية، تنظيف HTML
        if ($request->item_type === 'rich_text' && isset($content['html'])) {
            $content['html'] = clean_html($content['html'] ?? '');
        }
        
        // للجداول، التأكد من البيانات
        if ($request->item_type === 'table' && isset($content['table_data'])) {
            // table_data يأتي كـ JSON string
            if (is_string($content['table_data'])) {
                $content['table_data'] = json_decode($content['table_data'], true);
            }
        }

        HomeSectionItem::create([
            'home_section_id' => $homeSection->id,
            'item_type' => $request->item_type,
            'content' => $content,
            'media_path' => $mediaPath,
            'youtube_url' => $request->youtube_url,
            'display_order' => $lastOrder + 1,
            'settings' => $request->settings ?? [],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم إضافة العنصر بنجاح']);
        }

        return redirect()->route('dashboard.home-sections.edit', $homeSection)
            ->with('success', 'تم إضافة العنصر بنجاح');
    }

    /**
     * تحديث عنصر
     */
    public function update(Request $request, HomeSection $homeSection, HomeSectionItem $homeSectionItem)
    {
        $request->validate([
            'item_type' => 'required|string|max:255',
            'content' => 'nullable|array',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg,mp4,avi,mov|max:20480',
            'youtube_url' => 'nullable|url|max:500',
            'settings' => 'nullable|array',
        ]);

        // رفع ملف جديد إذا تم رفعه
        if ($request->hasFile('media')) {
            // حذف الملف القديم
            if ($homeSectionItem->media_path && !filter_var($homeSectionItem->media_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($homeSectionItem->media_path);
            }
            
            $folder = in_array($request->item_type, ['video', 'video_section']) ? 'home-sections/videos' : 'home-sections/images';
            $mediaPath = $request->file('media')->store($folder, 'public');
            $homeSectionItem->media_path = $mediaPath;
            $homeSectionItem->save();
        }

        // تنظيف المحتوى
        $content = $request->content ?? [];
        
        if ($request->item_type === 'rich_text' && isset($content['html'])) {
            $content['html'] = clean_html($content['html'] ?? '');
        }
        
        if ($request->item_type === 'table' && isset($content['table_data'])) {
            if (is_string($content['table_data'])) {
                $content['table_data'] = json_decode($content['table_data'], true);
            }
        }

        $homeSectionItem->update([
            'item_type' => $request->item_type,
            'content' => $content,
            'youtube_url' => $request->youtube_url,
            'settings' => $request->settings ?? [],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث العنصر بنجاح']);
        }

        return redirect()->route('dashboard.home-sections.edit', $homeSection)
            ->with('success', 'تم تحديث العنصر بنجاح');
    }

    /**
     * حذف عنصر
     */
    public function destroy(HomeSection $homeSection, HomeSectionItem $homeSectionItem)
    {
        // حذف الملف من التخزين
        if ($homeSectionItem->media_path && !filter_var($homeSectionItem->media_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($homeSectionItem->media_path);
        }
        
        $homeSectionItem->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم حذف العنصر بنجاح']);
        }

        return redirect()->route('dashboard.home-sections.edit', $homeSection)
            ->with('success', 'تم حذف العنصر بنجاح');
    }

    /**
     * إعادة ترتيب العناصر
     */
    public function reorder(Request $request, HomeSection $homeSection)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:home_section_items,id',
        ]);

        DB::transaction(function () use ($request, $homeSection) {
            foreach ($request->orders as $order => $itemId) {
                HomeSectionItem::where('id', $itemId)
                    ->where('home_section_id', $homeSection->id)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }
}

/**
 * دالة مساعدة لتنظيف HTML
 */
if (!function_exists('clean_html')) {
    function clean_html($html)
    {
        // السماح بالتاجات الآمنة فقط
        $allowed = '<p><br><strong><b><em><i><u><s><strike><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><table><thead><tbody><tfoot><tr><th><td><blockquote><pre><code><hr><div><span><sup><sub><figure><figcaption><video><source><iframe>';
        return strip_tags($html, $allowed);
    }
}
