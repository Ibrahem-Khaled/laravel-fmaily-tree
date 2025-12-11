<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = 15; // عدد الفئات في كل صفحة

        // إحصائيات
        $imagesCount = Image::whereNotNull('category_id')->count();
        $categoriesWithImages = Category::whereHas('images')->count();

        // دالة recursive للتحقق من وجود صور في الفئة أو أي من فئاتها الفرعية
        $hasImages = function ($category) use (&$hasImages) {
            // التحقق من وجود صور مباشرة في الفئة
            if (Image::where('category_id', $category->id)->exists()) {
                return true;
            }
            // التحقق من وجود صور في الفئات الفرعية
            $children = Category::where('parent_id', $category->id)->get();
            foreach ($children as $child) {
                if ($hasImages($child)) {
                    return true;
                }
            }
            return false;
        };

        // جلب الفئات التي تحتوي على صور مباشرة أو فئات فرعية تحتوي على صور
        $categoriesQuery = Category::query()
            ->where(function($q) {
                // فئات تحتوي على صور مباشرة
                $q->whereHas('images')
                  // أو فئات لديها فئات فرعية تحتوي على صور
                  ->orWhereHas('children', function($childQuery) {
                      $childQuery->whereHas('images')
                                 // أو فئات فرعية لديها فئات فرعية تحتوي على صور
                                 ->orWhereHas('children', function($grandChildQuery) {
                                     $grandChildQuery->whereHas('images');
                                 });
                  });
            })
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->with(['parent', 'children' => function($query) {
                $query->whereHas('images')
                      ->withCount('images')
                      ->orderBy('sort_order')
                      ->orderBy('name');
            }])
            ->withCount('images')
            ->orderBy('sort_order')
            ->orderBy('name');

        $categories = $categoriesQuery->paginate($perPage);

        // حساب العدد الكلي للصور لكل فئة (بما في ذلك الفئات الفرعية)
        $categories->getCollection()->transform(function($category) {
            // حساب عدد الصور في الفئة الرئيسية
            $directImagesCount = Image::where('category_id', $category->id)->count();

            // حساب عدد الصور في الفئات الفرعية
            $subcategoryImagesCount = 0;
            $subcategoryIds = Category::where('parent_id', $category->id)->pluck('id');
            if ($subcategoryIds->isNotEmpty()) {
                $subcategoryImagesCount = Image::whereIn('category_id', $subcategoryIds)->count();
            }

            $category->total_images_count = $directImagesCount + $subcategoryImagesCount;
            return $category;
        });

        // جلب الفئات لرفع الصور (للمودال)
        $categoriesForUpload = Category::orderBy('sort_order')->orderBy('name')->get();

        // جلب الأشخاص للمنشن في مودال رفع الصور
        $people = Person::get();

        return view('dashboard.gallery.index', compact(
            'search',
            'imagesCount',
            'categoriesWithImages',
            'categories',
            'categoriesForUpload',
            'people'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'images.*'    => ['nullable', 'image', 'max:150000'], // 150MB limit
            'pdfs.*'      => ['nullable', 'file', 'mimes:pdf', 'max:50000'], // 50MB limit for PDFs
            'youtube_urls.*' => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)/'],
            'media_types.*' => ['nullable', 'in:image,youtube,pdf'],
            'mentioned_persons' => ['nullable', 'array'],
            'mentioned_persons.*' => ['exists:persons,id'],
            'thumbnails.*' => ['nullable', 'image', 'max:10000'], // 10MB limit for thumbnails
            'youtube_thumbnails.*' => ['nullable', 'image', 'max:10000'], // 10MB limit for YouTube thumbnails
        ]);

        DB::transaction(function () use ($request) {
            // Handle image uploads
            foreach ($request->file('images', []) as $file) {
                $path = $file->store('categories', 'public');
                $image = Image::create([
                    'name'        => $file->getClientOriginalName(),
                    'path'        => $path,
                    'media_type'  => 'image',
                    'file_size'   => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'category_id' => $request->category_id,
                ]);

                // ربط الأشخاص المذكورين بالصورة مع الترتيب
                if ($request->has('mentioned_persons') && is_array($request->mentioned_persons)) {
                    $personsWithOrder = [];
                    foreach ($request->mentioned_persons as $index => $personId) {
                        $personsWithOrder[$personId] = ['order' => $index + 1];
                    }
                    $image->mentionedPersons()->attach($personsWithOrder);
                }
            }

            // Handle PDF uploads
            $pdfFiles = array_values($request->file('pdfs', []));
            $thumbnailFiles = array_values($request->file('thumbnails', []));

            foreach ($pdfFiles as $index => $file) {
                $path = $file->store('categories', 'public');

                // Handle thumbnail if provided
                $thumbnailPath = null;
                if (isset($thumbnailFiles[$index])) {
                    $thumbnailPath = $thumbnailFiles[$index]->store('thumbnails', 'public');
                }

                $image = Image::create([
                    'name'        => $file->getClientOriginalName(),
                    'path'        => $path,
                    'thumbnail_path' => $thumbnailPath,
                    'media_type'  => 'pdf',
                    'file_size'   => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'category_id' => $request->category_id,
                ]);

                // ربط الأشخاص المذكورين بالملف مع الترتيب
                if ($request->has('mentioned_persons') && is_array($request->mentioned_persons)) {
                    $personsWithOrder = [];
                    foreach ($request->mentioned_persons as $index => $personId) {
                        $personsWithOrder[$personId] = ['order' => $index + 1];
                    }
                    $image->mentionedPersons()->attach($personsWithOrder);
                }
            }

            // Handle YouTube URLs
            $youtubeUrls = $request->input('youtube_urls', []);
            $youtubeNames = $request->input('youtube_names', []);
            $youtubeThumbnails = array_values($request->file('youtube_thumbnails', []));

            foreach ($youtubeUrls as $index => $url) {
                if (!empty($url)) {
                    // Handle thumbnail if provided
                    $thumbnailPath = null;
                    if (isset($youtubeThumbnails[$index])) {
                        $thumbnailPath = $youtubeThumbnails[$index]->store('thumbnails', 'public');
                    }

                    $image = Image::create([
                        'name'        => $youtubeNames[$index] ?? 'فيديو يوتيوب',
                        'youtube_url'  => $url,
                        'thumbnail_path' => $thumbnailPath,
                        'media_type'  => 'youtube',
                        'category_id' => $request->category_id,
                    ]);

                    // ربط الأشخاص المذكورين بالفيديو مع الترتيب
                    if ($request->has('mentioned_persons') && is_array($request->mentioned_persons)) {
                        $personsWithOrder = [];
                        foreach ($request->mentioned_persons as $index => $personId) {
                            $personsWithOrder[$personId] = ['order' => $index + 1];
                        }
                        $image->mentionedPersons()->attach($personsWithOrder);
                    }
                }
            }
        });

        return back()->with('success', 'تم رفع الملفات للفئة بنجاح.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => ['required', 'array']]);

        $ids = $request->input('ids', []);
        $images = Image::whereIn('id', $ids)
            ->whereNotNull('category_id') // تأكيد أنها ضمن المعرض
            ->get();

        DB::transaction(function () use ($images) {
            foreach ($images as $image) {
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }
        });

        return back()->with('success', 'تم حذف الصور المحددة.');
    }

    /**
     * نقل جماعي للصور إلى فئة جديدة
     */
    public function bulkMove(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:images,id'],
            'target_category_id' => ['required', 'exists:categories,id']
        ]);

        $ids = $request->input('ids', []);
        $targetCategoryId = $request->input('target_category_id');

        $images = Image::whereIn('id', $ids)
            ->whereNotNull('category_id') // تأكيد أنها ضمن المعرض
            ->get();

        if ($images->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على صور صحيحة للنقل'
            ], 400);
        }

        DB::transaction(function () use ($images, $targetCategoryId) {
            foreach ($images as $image) {
                $image->update(['category_id' => $targetCategoryId]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'تم نقل ' . $images->count() . ' صورة بنجاح'
        ]);
    }


    public function storeForArticle(Request $request, Article $article)
    {
        $request->validate(['images.*' => ['required', 'image', 'max:150000']]); // 150MB limit

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('articles', 'public');
            Image::create([
                'name'       => $file->getClientOriginalName(),
                'path'       => $path,
                'article_id' => $article->id,
            ]);
        }
        return back()->with('success', 'تم رفع الصور للمقال.');
    }

    public function edit(Image $image)
    {
        $image->load('mentionedPersons');

        return response()->json([
            'success' => true,
            'image' => [
                'id' => $image->id,
                'name' => $image->name,
                'description' => $image->description,
                'path' => $image->path,
                'thumbnail_path' => $image->thumbnail_path,
                'youtube_url' => $image->youtube_url,
                'media_type' => $image->media_type,
                'file_size' => $image->file_size,
                'file_extension' => $image->file_extension,
                'category_id' => $image->category_id,
                'is_program' => $image->is_program,
                'program_title' => $image->program_title,
                'program_description' => $image->program_description,
                'program_order' => $image->program_order,
                'mentioned_persons' => $image->mentionedPersons->pluck('id')->toArray(),
            ]
        ]);
    }

    public function update(Request $request, Image $image)
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:150000'], // 150MB limit
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:50000'], // 50MB limit for PDFs
            'youtube_url' => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)/'],
            'media_type' => ['nullable', 'in:image,youtube,pdf'],
            'mentioned_persons' => ['nullable', 'array'],
            'mentioned_persons.*' => ['exists:persons,id'],
            'pdf_thumbnail' => ['nullable', 'image', 'max:10000'], // 10MB limit for PDF thumbnails
            'youtube_thumbnail' => ['nullable', 'image', 'max:10000'], // 10MB limit for YouTube thumbnails
            'is_program' => ['nullable', 'boolean'],
            'program_title' => ['nullable', 'string', 'max:255'],
            'program_description' => ['nullable', 'string'],
            'program_order' => ['nullable', 'integer'],
        ]);

        DB::transaction(function () use ($request, $image) {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'is_program' => $request->has('is_program') ? (bool)$request->is_program : false,
                'program_title' => $request->program_title,
                'program_description' => $request->program_description,
                'program_order' => $request->program_order ?? 0,
            ];

            // إذا تم رفع صورة جديدة
            if ($request->hasFile('image')) {
                // حذف الملف القديم
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }

                // رفع الصورة الجديدة
                $file = $request->file('image');
                $path = $file->store('categories', 'public');
                $data['path'] = $path;
                $data['media_type'] = 'image';
                $data['file_size'] = $file->getSize();
                $data['file_extension'] = $file->getClientOriginalExtension();
                $data['youtube_url'] = null; // Clear YouTube URL when uploading image
                $data['name'] = $data['name'] ?: $file->getClientOriginalName();
            }

            // إذا تم رفع ملف PDF جديد
            if ($request->hasFile('pdf')) {
                // حذف الملف القديم
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }

                // رفع ملف PDF الجديد
                $file = $request->file('pdf');
                $path = $file->store('categories', 'public');
                $data['path'] = $path;
                $data['media_type'] = 'pdf';
                $data['file_size'] = $file->getSize();
                $data['file_extension'] = $file->getClientOriginalExtension();
                $data['youtube_url'] = null; // Clear YouTube URL when uploading PDF
                $data['name'] = $data['name'] ?: $file->getClientOriginalName();
            }

            // إذا تم إدخال رابط يوتيوب
            if ($request->filled('youtube_url')) {
                $data['youtube_url'] = $request->youtube_url;
                $data['media_type'] = 'youtube';
                $data['path'] = null; // Clear image path when using YouTube
            }

            // إذا تم رفع صورة مصغرة جديدة
            if ($request->hasFile('pdf_thumbnail') || $request->hasFile('youtube_thumbnail')) {
                // حذف الصورة المصغرة القديمة
                if ($image->thumbnail_path && Storage::disk('public')->exists($image->thumbnail_path)) {
                    Storage::disk('public')->delete($image->thumbnail_path);
                }

                // رفع الصورة المصغرة الجديدة
                $thumbnailFile = $request->file('pdf_thumbnail') ?? $request->file('youtube_thumbnail');
                $thumbnailPath = $thumbnailFile->store('thumbnails', 'public');
                $data['thumbnail_path'] = $thumbnailPath;
            }

            // إذا تم طلب حذف الصورة المصغرة
            if ($request->has('remove_pdf_thumbnail') || $request->has('remove_youtube_thumbnail')) {
                if ($image->thumbnail_path && Storage::disk('public')->exists($image->thumbnail_path)) {
                    Storage::disk('public')->delete($image->thumbnail_path);
                }
                $data['thumbnail_path'] = null;
            }

            $image->update($data);

            // تحديث الأشخاص المذكورين مع الترتيب
            if ($request->has('mentioned_persons')) {
                $personsWithOrder = [];
                foreach ($request->mentioned_persons as $index => $personId) {
                    $personsWithOrder[$personId] = ['order' => $index + 1];
                }
                $image->mentionedPersons()->sync($personsWithOrder);
            } else {
                $image->mentionedPersons()->detach();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصورة بنجاح'
        ]);
    }

    public function destroy(Image $image)
    {
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
        return back()->with('success', 'تم حذف الصورة.');
    }

    /**
     * حذف شخص من الصورة
     */
    public function removePerson(Image $image, Person $person)
    {
        // التحقق من أن الشخص مرتبط بالصورة
        if (!$image->mentionedPersons()->where('person_id', $person->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الشخص غير مرتبط بهذه الصورة'
            ], 404);
        }

        // حذف العلاقة
        $image->mentionedPersons()->detach($person->id);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الشخص من الصورة بنجاح'
        ]);
    }

    /**
     * إعادة ترتيب الأشخاص المذكورين في الصورة
     */
    public function reorderPersons(Request $request, Image $image)
    {
        $request->validate([
            'person_ids' => ['required', 'array'],
            'person_ids.*' => ['exists:persons,id']
        ]);

        $personIds = $request->input('person_ids', []);

        // التحقق من أن جميع الأشخاص مرتبطين بالصورة
        $existingPersonIds = $image->mentionedPersons()->pluck('person_id')->toArray();

        // التحقق من أن عدد الأشخاص المرسلين يطابق الموجودين
        if (count($personIds) !== count($existingPersonIds)) {
            return response()->json([
                'success' => false,
                'message' => 'عدد الأشخاص المرسلين لا يطابق الموجودين في الصورة'
            ], 400);
        }

        foreach ($personIds as $personId) {
            if (!in_array($personId, $existingPersonIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'بعض الأشخاص غير مرتبطين بهذه الصورة'
                ], 400);
            }
        }

        try {
            // تحديث الترتيب
            $personsWithOrder = [];
            foreach ($personIds as $index => $personId) {
                $personsWithOrder[$personId] = ['order' => $index + 1];
            }

            $image->mentionedPersons()->sync($personsWithOrder);

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة ترتيب الأشخاص بنجاح',
                'new_order' => $personIds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعادة الترتيب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض صفحة فئة معينة مع جميع صورها مع pagination
     */
    public function showCategory(Request $request, Category $category)
    {
        $search = $request->get('search');
        $personSearch = $request->get('person_search');
        $perPage = 20; // عدد الصور في كل صفحة

        // التحقق من أن الفئة لديها صور
        $imagesCount = Image::where('category_id', $category->id)->count();

        if ($imagesCount === 0) {
            return redirect()->route('dashboard.images.index')
                ->with('warning', 'هذه الفئة لا تحتوي على صور');
        }

        // جلب الصور مع pagination
        $imagesQuery = Image::with(['category.parent', 'mentionedPersons'])
            ->where('category_id', $category->id)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($personSearch, fn($q) => $q->whereHas('mentionedPersons', function($query) use ($personSearch) {
                $query->where('person_id', $personSearch);
            }));

        $images = $imagesQuery->latest('id')->paginate($perPage);

        // جلب جميع الفئات للنقل
        $allCategoriesForMove = Category::with('parent')
            ->where('id', '!=', $category->id) // استثناء الفئة الحالية
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // جلب الفئات لرفع الصور (للمودال)
        $categories = Category::whereHas('images')
            ->withCount('images')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // جلب الأشخاص للمنشن
        $people = Person::get();

        // حساب إحصائيات الفئة
        $categoryImagesCount = Image::where('category_id', $category->id)->count();

        // إذا كانت فئة فرعية، جلب الفئة الرئيسية
        $parentCategory = $category->parent;

        // جلب الفئات الفرعية لهذه الفئة (إذا كانت رئيسية)
        $subcategories = [];
        if (!$parentCategory) {
            $subcategories = Category::where('parent_id', $category->id)
                ->withCount('images')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        }

        return view('dashboard.gallery.category', compact(
            'category',
            'parentCategory',
            'subcategories',
            'images',
            'imagesCount',
            'categoryImagesCount',
            'search',
            'personSearch',
            'allCategoriesForMove',
            'categories',
            'people'
        ));
    }

    /**
     * تحميل الصورة
     */
    public function download(Image $image)
    {
        if (!$image->path || !Storage::disk('public')->exists($image->path)) {
            abort(404, 'الصورة غير موجودة');
        }

        $filePath = storage_path('app/public/' . $image->path);
        $fileName = $image->name ?: 'صورة_' . $image->id . '.' . pathinfo($image->path, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }
}
