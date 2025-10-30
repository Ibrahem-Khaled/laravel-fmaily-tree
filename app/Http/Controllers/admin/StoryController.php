<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StoryController extends Controller
{
    /**
     * عرض قائمة القصص مع الإحصائيات والبحث.
     */
    public function index(Request $request)
    {
        $query = Story::with(['storyOwner', 'narrators'])
            ->orderBy('created_at', 'desc');

        // تطبيق البحث
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%")
                    ->orWhereHas('storyOwner', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('narrators', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $stories = $query->paginate(15)->withQueryString();

        // حساب الإحصائيات
        $storiesCount = Story::count();
        $storiesWithContent = Story::whereNotNull('content')->count();
        $storiesWithAudio = Story::whereNotNull('audio_path')->count();
        $storiesWithVideo = Story::where(function($q) {
            $q->whereNotNull('video_url')
              ->orWhereNotNull('video_path');
        })->count();

        return view('dashboard.stories.index', compact(
            'stories',
            'storiesCount',
            'storiesWithContent',
            'storiesWithAudio',
            'storiesWithVideo'
        ));
    }

    /**
     * تخزين قصة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'story_owner_id' => 'nullable|exists:persons,id',
            'content' => 'nullable|string',
            // 1GB limit (in kilobytes)
            'audio_path' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:1048576',
            'video_url' => 'nullable|url|max:500',
            'video_path' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:102400',
            'narrators' => 'nullable|array',
            'narrators.*' => 'exists:persons,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'البيانات المدخلة غير صحيحة، يرجى المحاولة مرة أخرى.');
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // معالجة رفع الملف الصوتي
            if ($request->hasFile('audio_path')) {
                $data['audio_path'] = $request->file('audio_path')->store('stories/audio', 'public');
            }

            // معالجة نوع الفيديو
            $videoType = $data['video_type'] ?? 'none';
            if ($videoType === 'url') {
                // إذا كان رابط خارجي، لا نرفع ملف
                $data['video_path'] = null;
            } elseif ($videoType === 'file' && $request->hasFile('video_path')) {
                // رفع ملف فيديو
                $data['video_path'] = $request->file('video_path')->store('stories/videos', 'public');
                $data['video_url'] = null;
            } else {
                // لا يوجد فيديو
                $data['video_path'] = null;
                $data['video_url'] = null;
            }

            // إزالة حقول غير موجودة في الجدول
            unset($data['video_type']);

            // إزالة narrators من البيانات لأنها سيتم إضافتها بعد الإنشاء
            $narrators = $data['narrators'] ?? [];
            unset($data['narrators']);

            // إنشاء القصة
            $story = Story::create($data);

            // إضافة الرواة
            if (!empty($narrators)) {
                $story->narrators()->attach($narrators);
            }

            DB::commit();

            return redirect()->route('stories.index')
                ->with('success', 'تمت إضافة القصة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();

            // حذف الملفات المرفوعة في حالة الخطأ
            if (isset($data['audio_path']) && Storage::disk('public')->exists($data['audio_path'])) {
                Storage::disk('public')->delete($data['audio_path']);
            }
            if (isset($data['video_path']) && Storage::disk('public')->exists($data['video_path'])) {
                Storage::disk('public')->delete($data['video_path']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة القصة: ' . $e->getMessage());
        }
    }

    /**
     * تحديث بيانات قصة محددة.
     */
    public function update(Request $request, Story $story)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'story_owner_id' => 'nullable|exists:persons,id',
            'content' => 'nullable|string',
            // 1GB limit (in kilobytes)
            'audio_path' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:1048576',
            'video_url' => 'nullable|url|max:500',
            'video_path' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:102400',
            'narrators' => 'nullable|array',
            'narrators.*' => 'exists:persons,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'البيانات المدخلة غير صحيحة، يرجى المحاولة مرة أخرى.');
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // معالجة رفع الملف الصوتي الجديد
            if ($request->hasFile('audio_path')) {
                // حذف الملف القديم إذا كان موجوداً
                if ($story->audio_path) {
                    Storage::disk('public')->delete($story->audio_path);
                }
                $data['audio_path'] = $request->file('audio_path')->store('stories/audio', 'public');
            }

            // معالجة نوع الفيديو
            $videoType = $data['video_type'] ?? 'none';
            if ($videoType === 'url') {
                // رابط خارجي
                if (!$request->filled('video_url')) {
                    $data['video_url'] = null;
                }
                // حذف ملف الفيديو القديم إن وجد
                if ($story->video_path) {
                    Storage::disk('public')->delete($story->video_path);
                    $data['video_path'] = null;
                }
            } elseif ($videoType === 'file' && $request->hasFile('video_path')) {
                // رفع ملف فيديو جديد
                if ($story->video_path) {
                    Storage::disk('public')->delete($story->video_path);
                }
                $data['video_path'] = $request->file('video_path')->store('stories/videos', 'public');
                $data['video_url'] = null;
            } elseif ($videoType === 'none') {
                // لا يوجد فيديو - حذف جميع الفيديوهات
                if ($story->video_path) {
                    Storage::disk('public')->delete($story->video_path);
                }
                $data['video_path'] = null;
                $data['video_url'] = null;
            }

            // إزالة video_type لأنه ليس حقل في قاعدة البيانات
            unset($data['video_type']);

            // إزالة narrators من البيانات
            $narrators = $data['narrators'] ?? [];
            unset($data['narrators']);

            // تحديث القصة
            $story->update($data);

            // تحديث الرواة
            $story->narrators()->sync($narrators ?? []);

            DB::commit();

            return redirect()->route('stories.index')
                ->with('success', 'تم تحديث القصة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث القصة: ' . $e->getMessage());
        }
    }

    /**
     * حذف قصة محددة من قاعدة البيانات.
     */
    public function destroy(Story $story)
    {
        DB::beginTransaction();
        try {
            // حذف الملفات المرتبطة
            if ($story->audio_path && Storage::disk('public')->exists($story->audio_path)) {
                Storage::disk('public')->delete($story->audio_path);
            }

            if ($story->video_path && Storage::disk('public')->exists($story->video_path)) {
                Storage::disk('public')->delete($story->video_path);
            }

            // حذف علاقات الرواة
            $story->narrators()->detach();

            // حذف القصة
            $story->delete();

            DB::commit();

            return redirect()->route('stories.index')
                ->with('success', 'تم حذف القصة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف القصة: ' . $e->getMessage());
        }
    }
}

