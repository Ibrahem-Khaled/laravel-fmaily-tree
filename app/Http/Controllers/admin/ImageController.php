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
        $search     = $request->get('search');
        $categoryId = $request->get('category_id');
        $personSearch = $request->get('person_search');

        // إحصائيات
        $imagesCount          = Image::whereNotNull('category_id')->count();
        $categoriesWithImages = Category::whereHas('images')->count();

        // فئات لديها صور فقط
        $categories = Category::whereHas('images')
            ->withCount('images')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // صور المعرض (خاص بالفئات فقط)
        $imagesQ = Image::with(['category', 'mentionedPersons'])
            ->whereNotNull('category_id')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($personSearch, fn($q) => $q->whereHas('mentionedPersons', function($query) use ($personSearch) {
                $query->where('person_id', $personSearch);
            }));

        if ($categoryId) {
            $imagesQ->where('category_id', $categoryId);
        }

        $images = $imagesQ->latest('id')->paginate(24)->withQueryString();

        // جلب الأشخاص للمنشن في مودال رفع الصور
        $people = Person::get();

        return view('dashboard.gallery.index', compact(
            'search',
            'categoryId',
            'images',
            'imagesCount',
            'categoriesWithImages',
            'categories',
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
            foreach ($request->file('pdfs', []) as $file) {
                $path = $file->store('categories', 'public');
                $image = Image::create([
                    'name'        => $file->getClientOriginalName(),
                    'path'        => $path,
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

            foreach ($youtubeUrls as $index => $url) {
                if (!empty($url)) {
                    $image = Image::create([
                        'name'        => $youtubeNames[$index] ?? 'فيديو يوتيوب',
                        'youtube_url'  => $url,
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

        return back()->with('success', 'تم رفع الملفات للفئة.');
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
                'youtube_url' => $image->youtube_url,
                'media_type' => $image->media_type,
                'file_size' => $image->file_size,
                'file_extension' => $image->file_extension,
                'category_id' => $image->category_id,
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
        ]);

        DB::transaction(function () use ($request, $image) {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
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
