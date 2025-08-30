<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Models\Person;
use Illuminate\Support\Facades\DB;



class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $status     = $request->get('status', 'all');
        $categoryId = $request->get('category_id');
        $search     = $request->get('search');
        $personId   = $request->get('person_id'); // <-- فلترة اختيارية بالناشر

        $query = Article::with(['category', 'person'])
            ->withCount('images')  // <-- لتفادي N+1 عند عدّ الصور
            ->search($search)
            ->inCategory($categoryId)
            ->byPerson($personId);

        if ($status !== 'all') {
            $query->status($status);
        }

        $articles = $query->latest('id')->paginate(12)->withQueryString();

        // إحصائيات
        $articlesCount   = Article::count();
        $publishedCount  = Article::where('status', 'published')->count();
        $draftCount      = Article::where('status', 'draft')->count();
        $categoriesCount = Category::count();

        // فئات لديها مقالات
        $categories = Category::whereHas('articles')
            ->withCount('articles')
            ->ordered()
            ->get();

        // قائمة الناشرين لاختيارها في الفلاتر والفورم
        $people = Person::orderBy('first_name')->get();

        return view('dashboard.articles.index', compact(
            'articles',
            'status',
            'search',
            'categoryId',
            'personId',
            'articlesCount',
            'publishedCount',
            'draftCount',
            'categoriesCount',
            'categories',
            'people'
        ));
    }

    public function store(StoreArticleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $article = Article::create($request->validated());

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('articles', 'public');
                    Image::create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'article_id' => $article->id,
                    ]);
                }
            }
        });

        return back()->with('success', 'تم إنشاء المقال بنجاح');
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        DB::transaction(function () use ($request, $article) {
            $article->update($request->validated());

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('articles', 'public');
                    Image::create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'article_id' => $article->id,
                    ]);
                }
            }
        });

        return back()->with('success', 'تم تحديث المقال بنجاح');
    }

    public function destroy(Article $article)
    {
        DB::transaction(function () use ($article) {
            foreach ($article->images as $img) {
                if ($img->path && Storage::disk('public')->exists("{$img->path}")) {
                    Storage::disk('public')->delete($img->path);
                }
                $img->delete();
            }
            $article->delete();
        });

        return back()->with('success', 'تم حذف المقال بنجاح');
    }
}
