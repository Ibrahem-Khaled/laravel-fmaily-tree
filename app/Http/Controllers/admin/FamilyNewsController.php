<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyNews;
use App\Models\FamilyNewsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FamilyNewsController extends Controller
{
    /**
     * عرض صفحة إدارة الأخبار
     */
    public function index()
    {
        $news = FamilyNews::with('images')
            ->orderBy('display_order')
            ->orderBy('published_at', 'desc')
            ->get();

        // إحصائيات الأخبار
        $stats = [
            'total' => FamilyNews::count(),
            'active' => FamilyNews::where('is_active', true)->count(),
            'inactive' => FamilyNews::where('is_active', false)->count(),
            'published' => FamilyNews::where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
                })
                ->count(),
            'total_views' => FamilyNews::sum('views_count'),
        ];

        return view('dashboard.family-news.index', compact('news', 'stats'));
    }

    /**
     * عرض صفحة إضافة خبر جديد
     */
    public function create()
    {
        return view('dashboard.family-news.create');
    }

    /**
     * إضافة خبر جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('family-news', 'public');
        }

        $lastOrder = FamilyNews::max('display_order') ?? 0;

        $news = FamilyNews::create([
            'title' => $request->title,
            'content' => $request->content,
            'summary' => $request->summary,
            'main_image_path' => $mainImagePath,
            'published_at' => $request->published_at ? now()->parse($request->published_at) : now(),
            'display_order' => $lastOrder + 1,
            'is_active' => $request->boolean('is_active'),
            'views_count' => 0,
        ]);

        return redirect()->route('dashboard.family-news.index')
            ->with('success', 'تم إضافة الخبر بنجاح');
    }

    /**
     * عرض صفحة تعديل خبر
     */
    public function edit(FamilyNews $familyNews)
    {
        $familyNews->load('images');
        return view('dashboard.family-news.edit', compact('familyNews'));
    }

    /**
     * تحديث خبر
     */
    public function update(Request $request, FamilyNews $familyNews)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('main_image')) {
            // حذف الصورة القديمة
            if ($familyNews->main_image_path) {
                Storage::disk('public')->delete($familyNews->main_image_path);
            }

            $mainImagePath = $request->file('main_image')->store('family-news', 'public');
            $familyNews->main_image_path = $mainImagePath;
        }

        $familyNews->update([
            'title' => $request->title,
            'content' => $request->content,
            'summary' => $request->summary,
            'published_at' => $request->published_at ? now()->parse($request->published_at) : $familyNews->published_at,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.family-news.index')
            ->with('success', 'تم تحديث الخبر بنجاح');
    }

    /**
     * حذف خبر
     */
    public function destroy(FamilyNews $familyNews)
    {
        // حذف الصور الفرعية
        foreach ($familyNews->images as $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // حذف الصورة الرئيسية
        if ($familyNews->main_image_path) {
            Storage::disk('public')->delete($familyNews->main_image_path);
        }

        $familyNews->delete();

        return redirect()->route('dashboard.family-news.index')
            ->with('success', 'تم حذف الخبر بنجاح');
    }

    /**
     * إعادة ترتيب الأخبار
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:family_news,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $newsId) {
                FamilyNews::where('id', $newsId)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * تفعيل/تعطيل خبر
     */
    public function toggle(FamilyNews $familyNews)
    {
        $familyNews->update([
            'is_active' => !$familyNews->is_active
        ]);

        return redirect()->route('dashboard.family-news.index')
            ->with('success', 'تم تحديث حالة الخبر بنجاح');
    }

    /**
     * إضافة صورة فرعية
     */
    public function addImage(Request $request, FamilyNews $familyNews)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('family-news/images', 'public');

        $lastOrder = FamilyNewsImage::where('family_news_id', $familyNews->id)
            ->max('display_order') ?? 0;

        FamilyNewsImage::create([
            'family_news_id' => $familyNews->id,
            'image_path' => $imagePath,
            'caption' => $request->caption,
            'display_order' => $lastOrder + 1,
        ]);

        return redirect()->route('dashboard.family-news.edit', $familyNews)
            ->with('success', 'تم إضافة الصورة بنجاح');
    }

    /**
     * حذف صورة فرعية
     */
    public function deleteImage(Request $request, FamilyNewsImage $image)
    {
        $familyNews = $image->news;

        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()->route('dashboard.family-news.edit', $familyNews)
            ->with('success', 'تم حذف الصورة بنجاح');
    }

    /**
     * إعادة ترتيب الصور الفرعية
     */
    public function reorderImages(Request $request, FamilyNews $familyNews)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:family_news_images,id',
        ]);

        DB::transaction(function () use ($request, $familyNews) {
            foreach ($request->orders as $order => $imageId) {
                FamilyNewsImage::where('id', $imageId)
                    ->where('family_news_id', $familyNews->id)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة ترتيب الصور بنجاح']);
    }
}
