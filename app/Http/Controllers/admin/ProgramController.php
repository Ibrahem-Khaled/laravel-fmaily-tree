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

class ProgramController extends Controller
{
    /**
     * عرض صفحة إدارة البرامج
     */
    public function index()
    {
        $programs = Image::where('is_program', true)
            ->orderBy('program_order')
            ->get();

        // إحصائيات البرامج
        $stats = [
            'total' => Image::where('is_program', true)->count(),
            'active' => Image::where('is_program', true)->where('program_is_active', true)->count(),
            'inactive' => Image::where('is_program', true)->where('program_is_active', false)->count(),
            'with_description' => Image::where('is_program', true)->whereNotNull('program_description')->count(),
            'recent' => Image::where('is_program', true)->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('dashboard.programs.index', compact('programs', 'stats'));
    }

    /**
     * إضافة برنامج جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'program_title' => 'required|string|max:255',
            'program_description' => 'nullable|string|max:10000',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('programs', 'public');

        $lastOrder = Image::where('is_program', true)->max('program_order') ?? 0;

        Image::create([
            'name' => $request->name ?? $request->program_title,
            'path' => $imagePath,
            'program_title' => $request->program_title,
            'program_description' => $request->program_description,
            'program_order' => $lastOrder + 1,
            'program_is_active' => true, // البرامج الجديدة مفعلة افتراضياً
            'is_program' => true,
            'media_type' => 'image',
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.programs.index')
            ->with('success', 'تم إضافة البرنامج بنجاح');
    }

    /**
     * تحديث برنامج
     */
    public function update(Request $request, Image $program)
    {
        abort_unless($program->is_program, 404);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'program_title' => 'required|string|max:255',
            'program_description' => 'nullable|string|max:10000',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($program->path) {
                Storage::disk('public')->delete($program->path);
            }

            $imagePath = $request->file('image')->store('programs', 'public');
            $program->path = $imagePath;
        }

        if ($request->hasFile('cover_image')) {
            // حذف صورة الغلاف القديمة
            if ($program->cover_image_path) {
                Storage::disk('public')->delete($program->cover_image_path);
            }

            $coverImagePath = $request->file('cover_image')->store('programs/covers', 'public');
            $program->cover_image_path = $coverImagePath;
        }

        $program->update([
            'name' => $request->name ?? $request->program_title,
            'program_title' => $request->program_title,
            'program_description' => $request->program_description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.programs.manage', $program)
            ->with('success', 'تم تحديث البرنامج بنجاح');
    }

    /**
     * جلب بيانات برنامج (للتعديل)
     */
    public function show(Image $program)
    {
        abort_unless($program->is_program, 404);

        return response()->json([
            'program_title' => $program->program_title,
            'program_description' => $program->program_description,
            'name' => $program->name,
            'image_url' => $program->path ? asset('storage/' . $program->path) : null,
            'category_id' => $program->category_id,
        ]);
    }

    /**
     * حذف برنامج
     */
    public function destroy(Image $program)
    {
        abort_unless($program->is_program, 404);

        if ($program->path) {
            Storage::disk('public')->delete($program->path);
        }

        if ($program->cover_image_path) {
            Storage::disk('public')->delete($program->cover_image_path);
        }

        $program->mediaItems->each(function (Image $media) {
            if ($media->path && $media->media_type !== 'youtube') {
                Storage::disk('public')->delete($media->path);
            }
            $media->delete();
        });

        $program->programLinks()->delete();

        // فك ربط البرامج الفرعية
        $program->subPrograms()->update(['program_id' => null]);

        $program->update([
            'is_program' => false,
            'program_title' => null,
            'program_description' => null,
            'program_order' => 0,
        ]);

        return redirect()->route('dashboard.programs.index')
            ->with('success', 'تم حذف البرنامج بنجاح');
    }

    /**
     * إعادة ترتيب البرامج
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:images,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $programId) {
                Image::where('id', $programId)
                    ->where('is_program', true)
                    ->update(['program_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * تفعيل/تعطيل برنامج
     */
    public function toggle(Image $program)
    {
        abort_unless($program->is_program, 404);

        $program->update([
            'program_is_active' => !$program->program_is_active
        ]);

        $status = $program->program_is_active ? 'تم تفعيل البرنامج بنجاح' : 'تم إلغاء تفعيل البرنامج بنجاح';

        return redirect()->route('dashboard.programs.index')
            ->with('success', $status);
    }

    /**
     * صفحة إدارة تفاصيل برنامج محدد.
     */
    public function manage(Image $program)
    {
        abort_unless($program->is_program, 404);

        $program->load(['mediaItems', 'programLinks', 'programGalleries', 'subPrograms']);

        $galleryMedia = $program->mediaItems->filter(function (Image $media) {
            return ($media->media_type === 'image' || is_null($media->media_type))
                && is_null($media->gallery_id)
                && !$media->is_program; // استبعاد البرامج الفرعية
        });

        $videoMedia = $program->mediaItems->filter(function (Image $media) {
            return $media->media_type === 'youtube';
        });

        return view('dashboard.programs.manage', [
            'program' => $program,
            'galleryMedia' => $galleryMedia,
            'videoMedia' => $videoMedia,
            'programLinks' => $program->programLinks,
            'programGalleries' => $program->programGalleries,
            'subPrograms' => $program->subPrograms,
        ]);
    }

    /**
     * إضافة وسائط للبرنامج (صور أو فيديوهات يوتيوب).
     */
    public function storeMedia(Request $request, Image $program)
    {
        abort_unless($program->is_program, 404);

        // التحقق من وجود صور عند اختيار نوع الوسيط صورة
        if ($request->media_type === 'image') {
            if (!$request->hasFile('images') && !$request->hasFile('image')) {
                return redirect()
                    ->route('dashboard.programs.manage', $program)
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

        $nextOrder = ($program->mediaItems()->max('program_media_order') ?? 0) + 1;

        if ($request->media_type === 'image') {
            $uploadedCount = 0;

            // دعم رفع عدة صور دفعة واحدة
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('programs/media', 'public');

                    $program->mediaItems()->create([
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
            // دعم رفع صورة واحدة (للتوافق مع الكود القديم)
            elseif ($request->hasFile('image')) {
                $path = $request->file('image')->store('programs/media', 'public');

                $program->mediaItems()->create([
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
            $program->mediaItems()->create([
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
            ->route('dashboard.programs.manage', $program)
            ->with('success', $message);
    }

    /**
     * تحديث وسيط من البرنامج.
     */
    public function updateMedia(Request $request, Image $program, Image $media)
    {
        // abort_unless($program->is_program && $media->program_id === $program->id, 404);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($media->path && $media->media_type !== 'youtube') {
                Storage::disk('public')->delete($media->path);
            }

            $path = $request->file('image')->store('programs/media', 'public');
            $media->path = $path;
        }

        $media->update([
            'name' => $request->name ?? $media->name,
            'description' => $request->description ?? $media->description,
        ]);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم تحديث الوسيط بنجاح');
    }

    /**
     * حذف وسيط من البرنامج.
     */
    public function destroyMedia(Image $program, Image $media)
    {
        // abort_unless($program->is_program && $media->program_id === $program->id, 404);

        if ($media->path && $media->media_type !== 'youtube') {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم حذف الوسيط بنجاح');
    }

    /**
     * إعادة ترتيب وسائط البرنامج.
     */
    public function reorderMedia(Request $request, Image $program)
    {
        abort_unless($program->is_program, 404);

        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:images,id',
        ]);

        DB::transaction(function () use ($request, $program) {
            foreach ($request->orders as $order => $mediaId) {
                Image::where('id', $mediaId)
                    ->where('program_id', $program->id)
                    ->update(['program_media_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * إضافة رابط خارجي للبرنامج.
     */
    public function storeLink(Request $request, Image $program)
    {
        abort_unless($program->is_program, 404);

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string|max:500',
        ]);

        $nextOrder = ($program->programLinks()->max('link_order') ?? 0) + 1;

        $program->programLinks()->create([
            'title' => $request->title,
            'url' => $request->url,
            'description' => $request->description,
            'link_order' => $nextOrder,
        ]);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم إضافة الرابط بنجاح');
    }

    /**
     * حذف رابط خارجي.
     */
    public function destroyLink(Image $program, ProgramLink $link)
    {
        abort_unless($program->is_program && $link->program_id === $program->id, 404);

        $link->delete();

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم حذف الرابط بنجاح');
    }

    /**
     * إعادة ترتيب الروابط.
     */
    public function reorderLinks(Request $request, Image $program)
    {
        // abort_unless($program->is_program, 404);

        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:program_links,id',
        ]);

        DB::transaction(function () use ($request, $program) {
            foreach ($request->orders as $order => $linkId) {
                ProgramLink::where('id', $linkId)
                    ->where('program_id', $program->id)
                    ->update(['link_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * إضافة معرض صور جديد للبرنامج.
     */
    public function storeGallery(Request $request, Image $program)
    {
        // abort_unless($program->is_program, 404);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $lastOrder = $program->programGalleries()->max('gallery_order') ?? 0;

        $gallery = $program->programGalleries()->create([
            'title' => $request->title,
            'description' => $request->description,
            'gallery_order' => $lastOrder + 1,
        ]);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم إنشاء المعرض بنجاح');
    }

    /**
     * تحديث معرض صور.
     */
    public function updateGallery(Request $request, Image $program, ProgramGallery $gallery)
    {
        // abort_unless($program->is_program, 404);
        // abort_unless($gallery->program_id === $program->id, 404);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم تحديث المعرض بنجاح');
    }

    /**
     * حذف معرض صور.
     */
    public function destroyGallery(Image $program, ProgramGallery $gallery)
    {
        // abort_unless($program->is_program, 404);
        // abort_unless($gallery->program_id === $program->id, 404);

        $gallery->delete();

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم حذف المعرض بنجاح');
    }

    /**
     * إضافة صور لمعرض محدد.
     */
    public function storeGalleryMedia(Request $request, Image $program, ProgramGallery $gallery)
    {
        // abort_unless($program->is_program, 404);
        // abort_unless($gallery->program_id === $program->id, 404);

        if (!$request->hasFile('images') && !$request->hasFile('image')) {
            return redirect()
                ->route('dashboard.programs.manage', $program)
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
                $path = $image->store('programs/media', 'public');

                $gallery->images()->create([
                    'name' => $request->title,
                    'description' => $request->description,
                    'path' => $path,
                    'media_type' => 'image',
                    'program_id' => $program->id,
                    'program_media_order' => $nextOrder++,
                    'is_program' => false,
                ]);
                $uploadedCount++;
            }
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('programs/media', 'public');

            $gallery->images()->create([
                'name' => $request->title,
                'description' => $request->description,
                'path' => $path,
                'media_type' => 'image',
                'program_id' => $program->id,
                'program_media_order' => $nextOrder,
                'is_program' => false,
            ]);
            $uploadedCount = 1;
        }

        $message = $uploadedCount > 1
            ? "تم رفع {$uploadedCount} صور للمعرض بنجاح"
            : "تم إضافة الصورة للمعرض بنجاح";

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', $message);
    }

    /**
     * تحديث صورة في معرض.
     */
    public function updateGalleryMedia(Request $request, Image $program, ProgramGallery $gallery, Image $media)
    {
        // abort_unless($program->is_program, 404);
        // abort_unless($gallery->program_id === $program->id, 404);
        // abort_unless($media->gallery_id === $gallery->id, 404);

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
            // حذف الصورة القديمة
            if ($media->path) {
                Storage::disk('public')->delete($media->path);
            }
            // رفع الصورة الجديدة
            $updateData['path'] = $request->file('image')->store('programs/media', 'public');
        }

        $media->update($updateData);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم تحديث الصورة بنجاح');
    }

    /**
     * حذف صورة من معرض.
     */
    public function destroyGalleryMedia(Image $program, ProgramGallery $gallery, Image $media)
    {
        // abort_unless($program->is_program, 404);
        // abort_unless($gallery->program_id === $program->id, 404);
        // abort_unless($media->gallery_id === $gallery->id, 404);

        if ($media->path) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم حذف الصورة من المعرض بنجاح');
    }

    /**
     * إضافة برنامج فرعي للبرنامج (ربط برنامج موجود ببرنامج آخر).
     */
    public function attachSubProgram(Request $request, Image $program)
    {
        abort_unless($program->is_program, 404);

        $request->validate([
            'sub_program_id' => 'required|exists:images,id',
        ]);

        $subProgram = Image::findOrFail($request->sub_program_id);

        // التأكد من أن البرنامج المحدد هو برنامج وليس وسيط
        abort_unless($subProgram->is_program, 404);

        // التأكد من عدم ربط البرنامج بنفسه
        abort_if($subProgram->id === $program->id, 400, 'لا يمكن ربط البرنامج بنفسه');

        // التأكد من عدم ربط البرنامج ببرنامج فرعي آخر
        if ($subProgram->program_id) {
            return redirect()
                ->route('dashboard.programs.manage', $program)
                ->withErrors(['sub_program_id' => 'هذا البرنامج مرتبط ببرنامج آخر بالفعل']);
        }

        // الحصول على آخر ترتيب
        $lastOrder = $program->subPrograms()->max('program_order') ?? 0;

        $subProgram->update([
            'program_id' => $program->id,
            'program_order' => $lastOrder + 1,
        ]);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم إضافة البرنامج الفرعي بنجاح');
    }

    /**
     * فك ربط برنامج فرعي من البرنامج.
     */
    public function detachSubProgram(Image $program, Image $subProgram)
    {
        abort_unless($program->is_program, 404);
        abort_unless($subProgram->is_program && $subProgram->program_id === $program->id, 404);

        $subProgram->update([
            'program_id' => null,
        ]);

        return redirect()
            ->route('dashboard.programs.manage', $program)
            ->with('success', 'تم فك ربط البرنامج الفرعي بنجاح');
    }

    /**
     * إعادة ترتيب البرامج الفرعية.
     */
    public function reorderSubPrograms(Request $request, Image $program)
    {
        abort_unless($program->is_program, 404);

        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:images,id',
        ]);

        DB::transaction(function () use ($request, $program) {
            foreach ($request->orders as $order => $subProgramId) {
                Image::where('id', $subProgramId)
                    ->where('program_id', $program->id)
                    ->where('is_program', true)
                    ->update(['program_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true]);
    }
}
