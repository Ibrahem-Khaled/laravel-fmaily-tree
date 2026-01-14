<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\ProgramLink;
use App\Models\ProgramGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProudOfController extends Controller
{
    /**
     * عرض صفحة إدارة نفتخر بهم
     */
    public function index()
    {
        $items = Image::where('is_proud_of', true)
            ->orderBy('proud_of_order')
            ->get();

        // إحصائيات
        $stats = [
            'total' => Image::where('is_proud_of', true)->count(),
            'active' => Image::where('is_proud_of', true)->where('proud_of_is_active', true)->count(),
            'inactive' => Image::where('is_proud_of', true)->where('proud_of_is_active', false)->count(),
            'with_description' => Image::where('is_proud_of', true)->whereNotNull('proud_of_description')->count(),
            'recent' => Image::where('is_proud_of', true)->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('dashboard.proud-of.index', compact('items', 'stats'));
    }

    /**
     * إضافة عنصر جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'proud_of_title' => 'required|string|max:255',
            'proud_of_description' => 'nullable|string|max:10000',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('proud-of', 'public');

        $lastOrder = Image::where('is_proud_of', true)->max('proud_of_order') ?? 0;

        Image::create([
            'name' => $request->name ?? $request->proud_of_title,
            'path' => $imagePath,
            'proud_of_title' => $request->proud_of_title,
            'proud_of_description' => $request->proud_of_description,
            'proud_of_order' => $lastOrder + 1,
            'proud_of_is_active' => true,
            'is_proud_of' => true,
            'media_type' => 'image',
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.proud-of.index')
            ->with('success', 'تم إضافة العنصر بنجاح');
    }

    /**
     * تحديث عنصر
     */
    public function update(Request $request, Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'proud_of_title' => 'required|string|max:255',
            'proud_of_description' => 'nullable|string|max:10000',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            if ($item->path) {
                Storage::disk('public')->delete($item->path);
            }

            $imagePath = $request->file('image')->store('proud-of', 'public');
            $item->path = $imagePath;
        }

        if ($request->hasFile('cover_image')) {
            if ($item->cover_image_path) {
                Storage::disk('public')->delete($item->cover_image_path);
            }

            $coverImagePath = $request->file('cover_image')->store('proud-of/covers', 'public');
            $item->cover_image_path = $coverImagePath;
        }

        $item->update([
            'name' => $request->name ?? $request->proud_of_title,
            'proud_of_title' => $request->proud_of_title,
            'proud_of_description' => $request->proud_of_description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم تحديث العنصر بنجاح');
    }

    /**
     * جلب بيانات عنصر (للتعديل)
     */
    public function show(Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        return response()->json([
            'proud_of_title' => $item->proud_of_title,
            'proud_of_description' => $item->proud_of_description,
            'name' => $item->name,
            'image_url' => $item->path ? asset('storage/' . $item->path) : null,
            'cover_image_url' => $item->cover_image_path ? asset('storage/' . $item->cover_image_path) : null,
            'category_id' => $item->category_id,
        ]);
    }

    /**
     * حذف عنصر
     */
    public function destroy(Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        if ($item->path) {
            Storage::disk('public')->delete($item->path);
        }

        if ($item->cover_image_path) {
            Storage::disk('public')->delete($item->cover_image_path);
        }

        $item->mediaItems->each(function (Image $media) {
            if ($media->path && $media->media_type !== 'youtube') {
                Storage::disk('public')->delete($media->path);
            }
            $media->delete();
        });

        $item->programLinks()->delete();

        $item->update([
            'is_proud_of' => false,
            'proud_of_title' => null,
            'proud_of_description' => null,
            'proud_of_order' => 0,
        ]);

        return redirect()->route('dashboard.proud-of.index')
            ->with('success', 'تم حذف العنصر بنجاح');
    }

    /**
     * إعادة ترتيب العناصر
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:images,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $itemId) {
                Image::where('id', $itemId)
                    ->where('is_proud_of', true)
                    ->update(['proud_of_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * تفعيل/تعطيل عنصر
     */
    public function toggle(Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        $item->update([
            'proud_of_is_active' => !$item->proud_of_is_active
        ]);

        $status = $item->proud_of_is_active ? 'تم تفعيل العنصر بنجاح' : 'تم إلغاء تفعيل العنصر بنجاح';

        return redirect()->route('dashboard.proud-of.index')
            ->with('success', $status);
    }

    /**
     * صفحة إدارة تفاصيل عنصر محدد.
     */
    public function manage(Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        $item->load(['mediaItems', 'programLinks', 'programGalleries']);

        $galleryMedia = $item->mediaItems->filter(function (Image $media) {
            return ($media->media_type === 'image' || is_null($media->media_type)) && is_null($media->gallery_id);
        });

        $videoMedia = $item->mediaItems->filter(function (Image $media) {
            return $media->media_type === 'youtube';
        });

        return view('dashboard.proud-of.manage', [
            'item' => $item,
            'galleryMedia' => $galleryMedia,
            'videoMedia' => $videoMedia,
            'programLinks' => $item->programLinks,
            'programGalleries' => $item->programGalleries,
        ]);
    }

    /**
     * إضافة وسائط للعنصر (صور أو فيديوهات يوتيوب).
     */
    public function storeMedia(Request $request, Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        if ($request->media_type === 'image') {
            if (!$request->hasFile('images') && !$request->hasFile('image')) {
                return redirect()
                    ->route('dashboard.proud-of.manage', $item)
                    ->withErrors(['images' => 'يرجى اختيار صورة واحدة على الأقل']);
            }
        }

        $request->validate([
            'media_type' => ['required', Rule::in(['image', 'youtube'])],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'youtube_url' => 'required_if:media_type,youtube|url|max:500',
        ], [
            'images.*.image' => 'يجب أن يكون الملف المرفوع صورة',
            'images.*.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'يجب ألا تتجاوز الصورة 5 ميجابايت',
            'youtube_url.required_if' => 'يرجى إدخال رابط يوتيوب عند اختيار نوع الوسيط فيديو',
        ]);

        $nextOrder = ($item->mediaItems()->max('program_media_order') ?? 0) + 1;

        if ($request->media_type === 'image') {
            $uploadedCount = 0;

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('proud-of/media', 'public');

                    $item->mediaItems()->create([
                        'name' => $request->title,
                        'description' => $request->description,
                        'path' => $path,
                        'media_type' => 'image',
                        'program_media_order' => $nextOrder++,
                        'is_program' => false,
                    ]);
                    $uploadedCount++;
                }
            }
            elseif ($request->hasFile('image')) {
                $path = $request->file('image')->store('proud-of/media', 'public');

                $item->mediaItems()->create([
                    'name' => $request->title,
                    'description' => $request->description,
                    'path' => $path,
                    'media_type' => 'image',
                    'program_media_order' => $nextOrder,
                    'is_program' => false,
                ]);
                $uploadedCount = 1;
            }

            $message = $uploadedCount > 1
                ? "تم رفع {$uploadedCount} صور بنجاح"
                : "تم إضافة الصورة بنجاح";
        } else {
            $item->mediaItems()->create([
                'name' => $request->title,
                'description' => $request->description,
                'media_type' => 'youtube',
                'youtube_url' => $request->youtube_url,
                'program_media_order' => $nextOrder,
                'is_program' => false,
            ]);
            $message = 'تم إضافة الفيديو بنجاح';
        }

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', $message);
    }

    /**
     * تحديث وسيط من العنصر.
     */
    public function updateMedia(Request $request, Image $item, Image $media)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($media->path && $media->media_type !== 'youtube') {
                Storage::disk('public')->delete($media->path);
            }

            $path = $request->file('image')->store('proud-of/media', 'public');
            $media->path = $path;
        }

        $media->update([
            'name' => $request->name ?? $media->name,
            'description' => $request->description ?? $media->description,
        ]);

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم تحديث الوسيط بنجاح');
    }

    /**
     * حذف وسيط من العنصر.
     */
    public function destroyMedia(Image $item, Image $media)
    {
        if ($media->path && $media->media_type !== 'youtube') {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم حذف الوسيط بنجاح');
    }

    /**
     * إعادة ترتيب وسائط العنصر.
     */
    public function reorderMedia(Request $request, Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:images,id',
        ]);

        DB::transaction(function () use ($request, $item) {
            foreach ($request->orders as $order => $mediaId) {
                Image::where('id', $mediaId)
                    ->where('program_id', $item->id)
                    ->update(['program_media_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * إضافة رابط خارجي للعنصر.
     */
    public function storeLink(Request $request, Image $item)
    {
        abort_unless($item->is_proud_of, 404);

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string|max:500',
        ]);

        $nextOrder = ($item->programLinks()->max('link_order') ?? 0) + 1;

        $item->programLinks()->create([
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description,
            'link_order' => $nextOrder,
        ]);

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم إضافة الرابط بنجاح');
    }

    /**
     * حذف رابط خارجي.
     */
    public function destroyLink(Image $item, ProgramLink $link)
    {
        abort_unless($item->is_proud_of && $link->program_id === $item->id, 404);

        $link->delete();

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم حذف الرابط بنجاح');
    }

    /**
     * إعادة ترتيب الروابط.
     */
    public function reorderLinks(Request $request, Image $item)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:program_links,id',
        ]);

        DB::transaction(function () use ($request, $item) {
            foreach ($request->orders as $order => $linkId) {
                ProgramLink::where('id', $linkId)
                    ->where('program_id', $item->id)
                    ->update(['link_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * إضافة معرض صور جديد للعنصر.
     */
    public function storeGallery(Request $request, Image $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $lastOrder = $item->programGalleries()->max('gallery_order') ?? 0;

        $gallery = $item->programGalleries()->create([
            'title' => $request->title,
            'description' => $request->description,
            'gallery_order' => $lastOrder + 1,
        ]);

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم إنشاء المعرض بنجاح');
    }

    /**
     * تحديث معرض صور.
     */
    public function updateGallery(Request $request, Image $item, ProgramGallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم تحديث المعرض بنجاح');
    }

    /**
     * حذف معرض صور.
     */
    public function destroyGallery(Image $item, ProgramGallery $gallery)
    {
        $gallery->delete();

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم حذف المعرض بنجاح');
    }

    /**
     * إضافة صور لمعرض محدد.
     */
    public function storeGalleryMedia(Request $request, Image $item, ProgramGallery $gallery)
    {
        if (!$request->hasFile('images') && !$request->hasFile('image')) {
            return redirect()
                ->route('dashboard.proud-of.manage', $item)
                ->withErrors(['images' => 'يرجى اختيار صورة واحدة على الأقل']);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'images.*.image' => 'يجب أن يكون الملف المرفوع صورة',
            'images.*.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'يجب ألا تتجاوز الصورة 5 ميجابايت',
        ]);

        $nextOrder = ($gallery->images()->max('program_media_order') ?? 0) + 1;
        $uploadedCount = 0;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('proud-of/media', 'public');

                $gallery->images()->create([
                    'name' => $request->title,
                    'description' => $request->description,
                    'path' => $path,
                    'media_type' => 'image',
                    'program_id' => $item->id,
                    'program_media_order' => $nextOrder++,
                    'is_program' => false,
                ]);
                $uploadedCount++;
            }
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('proud-of/media', 'public');

            $gallery->images()->create([
                'name' => $request->title,
                'description' => $request->description,
                'path' => $path,
                'media_type' => 'image',
                'program_id' => $item->id,
                'program_media_order' => $nextOrder,
                'is_program' => false,
            ]);
            $uploadedCount = 1;
        }

        $message = $uploadedCount > 1
            ? "تم رفع {$uploadedCount} صور للمعرض بنجاح"
            : "تم إضافة الصورة للمعرض بنجاح";

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', $message);
    }

    /**
     * تحديث صورة في معرض.
     */
    public function updateGalleryMedia(Request $request, Image $item, ProgramGallery $gallery, Image $media)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($media->path) {
                Storage::disk('public')->delete($media->path);
            }
            $updateData['path'] = $request->file('image')->store('proud-of/media', 'public');
        }

        $media->update($updateData);

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم تحديث الصورة بنجاح');
    }

    /**
     * حذف صورة من معرض.
     */
    public function destroyGalleryMedia(Image $item, ProgramGallery $gallery, Image $media)
    {
        if ($media->path) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return redirect()
            ->route('dashboard.proud-of.manage', $item)
            ->with('success', 'تم حذف الصورة من المعرض بنجاح');
    }
}
