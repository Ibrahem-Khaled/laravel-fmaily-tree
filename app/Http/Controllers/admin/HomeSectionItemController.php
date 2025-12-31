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
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov|max:10240', // 10MB max
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

        HomeSectionItem::create([
            'home_section_id' => $homeSection->id,
            'item_type' => $request->item_type,
            'content' => $request->content ?? [],
            'media_path' => $mediaPath,
            'youtube_url' => $request->youtube_url,
            'display_order' => $lastOrder + 1,
            'settings' => $request->settings ?? [],
        ]);

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
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov|max:10240',
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
        }

        $homeSectionItem->update([
            'item_type' => $request->item_type,
            'content' => $request->content ?? [],
            'youtube_url' => $request->youtube_url,
            'settings' => $request->settings ?? [],
        ]);

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
