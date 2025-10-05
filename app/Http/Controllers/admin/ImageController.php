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
        $people = Person::
            get();

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
            'images.*'    => ['required', 'image', 'max:4096'],
            'mentioned_persons' => ['nullable', 'array'],
            'mentioned_persons.*' => ['exists:persons,id'],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->file('images', []) as $file) {
                $path = $file->store('categories', 'public');
                $image = Image::create([
                    'name'        => $file->getClientOriginalName(),
                    'path'        => $path,
                    'category_id' => $request->category_id,
                ]);

                // ربط الأشخاص المذكورين بالصورة
                if ($request->has('mentioned_persons') && is_array($request->mentioned_persons)) {
                    $image->mentionedPersons()->attach($request->mentioned_persons);
                }
            }
        });

        return back()->with('success', 'تم رفع الصور للفئة.');
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
        $request->validate(['images.*' => ['required', 'image', 'max:4096']]);

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
            'image' => ['nullable', 'image', 'max:4096'],
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
                // حذف الصورة القديمة
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }

                // رفع الصورة الجديدة
                $path = $request->file('image')->store('categories', 'public');
                $data['path'] = $path;
                $data['name'] = $data['name'] ?: $request->file('image')->getClientOriginalName();
            }

            $image->update($data);

            // تحديث الأشخاص المذكورين
            if ($request->has('mentioned_persons')) {
                $image->mentionedPersons()->sync($request->mentioned_persons);
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
}
