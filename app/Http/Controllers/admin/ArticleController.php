<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;
use App\Models\Category;
use App\Models\Person;
use App\Models\Attachment;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status     = $request->get('status', 'all');
        $categoryId = $request->get('category_id');
        $search     = $request->get('search');
        $personId   = $request->get('person_id');

        // eager loading + عدادات
        $query = Article::with(['category', 'person', 'attachments'])
            ->withCount(['images', 'attachments'])
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

        // فئات للفلاتر
        $categories = Category::whereHas('articles')
            ->withCount('articles')
            ->ordered()
            ->get();

        // قائمة الناشرين
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            // معالجة created_at إذا تم إرساله
            if (isset($data['created_at']) && !empty($data['created_at'])) {
                $data['created_at'] = date('Y-m-d H:i:s', strtotime($data['created_at']));
            } else {
                // إزالة created_at من البيانات إذا كان فارغاً ليتم استخدام التاريخ التلقائي
                unset($data['created_at']);
            }
            
            $article = Article::create($data);

            // رفع الصور (أسماء آمنة عبر store())
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store("articles/{$article->id}/images", 'public');
                    Image::create([
                        'name'       => $file->getClientOriginalName(), // اسم للعرض فقط
                        'path'       => $path,
                        'article_id' => $article->id,
                    ]);
                }
            }

            // رفع المرفقات (Polymorphic)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store("articles/{$article->id}/attachments", 'public');
                    $article->attachments()->create([
                        'path'      => $path,
                        'file_name' => $file->getClientOriginalName(), // للعرض
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            // روابط الفيديو (يوتيوب) - دعم مصفوفة أو نص متعدد الأسطر
            $videos = (array) $request->input('videos', []);
            $videosText = (string) $request->input('videos_text', '');
            if (trim($videosText) !== '') {
                $videosFromText = preg_split("/~\r?\n|\r/", $videosText) ?: [];
                $videos = array_merge($videos, $videosFromText);
            }
            $this->syncVideos($article, $videos);
        });

        return back()->with('success', 'تم إنشاء المقال بنجاح');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        DB::transaction(function () use ($request, $article) {
            $data = $request->validated();
            
            // معالجة created_at إذا تم إرساله
            if (isset($data['created_at']) && !empty($data['created_at'])) {
                $data['created_at'] = date('Y-m-d H:i:s', strtotime($data['created_at']));
            } else {
                // إزالة created_at من البيانات إذا كان فارغاً
                unset($data['created_at']);
            }
            
            $article->update($data);

            // صور جديدة
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store("articles/{$article->id}/images", 'public');
                    Image::create([
                        'name'       => $file->getClientOriginalName(),
                        'path'       => $path,
                        'article_id' => $article->id,
                    ]);
                }
            }

            // مرفقات جديدة
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store("articles/{$article->id}/attachments", 'public');
                    $article->attachments()->create([
                        'path'      => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            // تحديث روابط الفيديو
            if ($request->hasAny(['videos', 'videos_text'])) {
                $videos = (array) $request->input('videos', []);
                $videosText = (string) $request->input('videos_text', '');
                if (trim($videosText) !== '') {
                    $videosFromText = preg_split("/~\r?\n|\r/", $videosText) ?: [];
                    $videos = array_merge($videos, $videosFromText);
                }
                $this->syncVideos($article, $videos);
            }
        });

        return back()->with('success', 'تم تحديث المقال بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // ملاحظة: لو عندك SoftDeletes في Article، فكّر تستخدم forceDelete عند الحاجة
        $article->delete();

        // تنظيف مجلد المقال بالكامل بعد حذف السجلات
        Storage::disk('public')->deleteDirectory("articles/{$article->id}");

        return back()->with('success', 'تم حذف المقال وكل مرفقاته بنجاح');
    }

    /**
     * حذف مرفق واحد فقط.
     */
    public function destroyAttachment(Attachment $attachment)
    {
        // $this->authorize('delete', $attachment);
        $attachment->delete();
        return back()->with('success', 'تم حذف المرفق بنجاح');
    }

    /**
     * تنزيل مرفق آمن.
     */
    public function downloadAttachment(Attachment $attachment)
    {
        // $this->authorize('view', $attachment);
        if (!$attachment->path || !Storage::disk('public')->exists($attachment->path)) {
            abort(Response::HTTP_NOT_FOUND, 'الملف غير موجود.');
        }

        $fullPath = storage_path('app/public/' . $attachment->path);
        if (!file_exists($fullPath)) {
            abort(Response::HTTP_NOT_FOUND, 'الملف غير موجود.');
        }
        return response()->download($fullPath, $attachment->file_name);
    }

    /**
     * حذف فيديو محدد من مقال.
     */
    public function destroyVideo(Article $article, Video $video)
    {
        if ($video->article_id !== $article->id) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $video->delete();
        return back()->with('success', 'تم حذف الفيديو بنجاح');
    }

    /**
     * مزامنة روابط الفيديو مع المقال.
     */
    protected function syncVideos(Article $article, array $rawUrls): void
    {
        // نظّف الإدخال: إزالة الفراغات، إزالة الفراغات الفارغة وتفريد
        $urls = collect($rawUrls)
            ->map(fn ($u) => is_string($u) ? trim($u) : null)
            ->filter()
            ->unique()
            ->values();

        // استخرج بيانات الفيديو
        $videosData = [];
        foreach ($urls as $index => $url) {
            $parsed = $this->parseVideoUrl($url);
            if (!$parsed) {
                continue;
            }
            $videosData[] = [
                'provider'   => $parsed['provider'],
                'video_id'   => $parsed['video_id'],
                'url'        => $url,
                'sort_order' => $index,
            ];
        }

        // احذف القديمة/أعد الإنشاء بشكل بسيط للحفاظ على الترتيب
        $article->videos()->delete();
        foreach ($videosData as $data) {
            $article->videos()->create($data);
        }
    }

    /**
     * يدعم YouTube: watch?v=، youtu.be/، shorts/.
     */
    protected function parseVideoUrl(string $url): ?array
    {
        // YouTube watch
        if (preg_match('~(?:youtube\.com/watch\?v=)([\w-]{6,})~i', $url, $m)) {
            return ['provider' => 'youtube', 'video_id' => $m[1]];
        }
        // YouTube short link
        if (preg_match('~(?:youtu\.be/)([\w-]{6,})~i', $url, $m)) {
            return ['provider' => 'youtube', 'video_id' => $m[1]];
        }
        // YouTube shorts
        if (preg_match('~(?:youtube\.com/shorts/)([\w-]{6,})~i', $url, $m)) {
            return ['provider' => 'youtube', 'video_id' => $m[1]];
        }

        return null;
    }
}
