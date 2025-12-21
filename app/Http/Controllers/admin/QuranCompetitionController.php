<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuranCompetition;
use App\Models\QuranCompetitionWinner;
use App\Models\QuranCompetitionMedia;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class QuranCompetitionController extends Controller
{
    /**
     * عرض قائمة المسابقات
     */
    public function index(): View
    {
        $competitions = QuranCompetition::ordered()->get();
        return view('dashboard.quran-competitions.index', compact('competitions'));
    }

    /**
     * عرض نموذج إضافة مسابقة جديدة
     */
    public function create(): View
    {
        return view('dashboard.quran-competitions.create');
    }

    /**
     * حفظ مسابقة جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hijri_year' => 'required|string|max:10',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'hijri_year.required' => 'السنة الهجرية مطلوبة',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'cover_image.image' => 'يجب أن يكون الملف صورة',
            'cover_image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('quran-competitions', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        QuranCompetition::create($validated);

        return redirect()->route('dashboard.quran-competitions.index')
            ->with('success', 'تم إضافة المسابقة بنجاح');
    }

    /**
     * عرض تفاصيل مسابقة مع إدارة الفائزين
     */
    public function show(QuranCompetition $quranCompetition): View
    {
        $quranCompetition->load(['winners.person', 'media']);
        $persons = Person::orderBy('first_name')->get();
        
        return view('dashboard.quran-competitions.show', compact('quranCompetition', 'persons'));
    }

    /**
     * عرض نموذج تعديل مسابقة
     */
    public function edit(QuranCompetition $quranCompetition): View
    {
        return view('dashboard.quran-competitions.edit', compact('quranCompetition'));
    }

    /**
     * تحديث مسابقة
     */
    public function update(Request $request, QuranCompetition $quranCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hijri_year' => 'required|string|max:10',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ], [
            'title.required' => 'عنوان المسابقة مطلوب',
            'hijri_year.required' => 'السنة الهجرية مطلوبة',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'cover_image.image' => 'يجب أن يكون الملف صورة',
            'cover_image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ]);

        if ($request->hasFile('cover_image')) {
            // حذف الصورة القديمة
            if ($quranCompetition->cover_image) {
                Storage::disk('public')->delete($quranCompetition->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('quran-competitions', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $quranCompetition->update($validated);

        return redirect()->route('dashboard.quran-competitions.index')
            ->with('success', 'تم تحديث المسابقة بنجاح');
    }

    /**
     * حذف مسابقة
     */
    public function destroy(QuranCompetition $quranCompetition): RedirectResponse
    {
        // حذف الصورة
        if ($quranCompetition->cover_image) {
            Storage::disk('public')->delete($quranCompetition->cover_image);
        }

        $quranCompetition->delete();

        return redirect()->route('dashboard.quran-competitions.index')
            ->with('success', 'تم حذف المسابقة بنجاح');
    }

    /**
     * إضافة فائز للمسابقة
     */
    public function addWinner(Request $request, QuranCompetition $quranCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:persons,id',
            'position' => 'required|integer|min:1',
            'category' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'person_id.required' => 'يجب اختيار الشخص',
            'person_id.exists' => 'الشخص المختار غير موجود',
            'position.required' => 'المركز مطلوب',
            'position.min' => 'المركز يجب أن يكون على الأقل 1',
        ]);

        // التحقق من عدم تكرار الشخص في نفس المسابقة
        $existing = QuranCompetitionWinner::where('competition_id', $quranCompetition->id)
            ->where('person_id', $validated['person_id'])
            ->first();

        if ($existing) {
            return back()->withErrors(['person_id' => 'هذا الشخص موجود بالفعل في المسابقة'])->withInput();
        }

        $validated['competition_id'] = $quranCompetition->id;
        QuranCompetitionWinner::create($validated);

        return back()->with('success', 'تم إضافة الفائز بنجاح');
    }

    /**
     * حذف فائز
     */
    public function removeWinner(QuranCompetitionWinner $winner): RedirectResponse
    {
        $competitionId = $winner->competition_id;
        $winner->delete();

        return redirect()->route('dashboard.quran-competitions.show', $competitionId)
            ->with('success', 'تم حذف الفائز بنجاح');
    }

    /**
     * إضافة وسائط للمسابقة
     */
    public function addMedia(Request $request, QuranCompetition $quranCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'media_type' => 'required|in:image,video,youtube',
            'file' => 'required_if:media_type,image,video|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov|max:10240',
            'youtube_url' => 'required_if:media_type,youtube|url',
            'caption' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
        ], [
            'media_type.required' => 'نوع الوسائط مطلوب',
            'file.required_if' => 'الملف مطلوب',
            'youtube_url.required_if' => 'رابط يوتيوب مطلوب',
            'youtube_url.url' => 'رابط يوتيوب غير صحيح',
        ]);

        $validated['competition_id'] = $quranCompetition->id;

        if ($request->hasFile('file')) {
            $folder = $validated['media_type'] === 'image' ? 'quran-competitions/images' : 'quran-competitions/videos';
            $validated['file_path'] = $request->file('file')->store($folder, 'public');
        }

        QuranCompetitionMedia::create($validated);

        return back()->with('success', 'تم إضافة الوسائط بنجاح');
    }

    /**
     * حذف وسائط
     */
    public function removeMedia(QuranCompetitionMedia $media): RedirectResponse
    {
        $competitionId = $media->competition_id;
        
        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return redirect()->route('dashboard.quran-competitions.show', $competitionId)
            ->with('success', 'تم حذف الوسائط بنجاح');
    }
}

