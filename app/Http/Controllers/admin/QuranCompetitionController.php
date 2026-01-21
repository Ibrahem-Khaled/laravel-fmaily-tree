<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuranCompetition;
use App\Models\QuranCompetitionWinner;
use App\Models\QuranCompetitionMedia;
use App\Models\QuranCompetitionSection;
use App\Models\Person;
use App\Models\Category;
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
        $competitions = QuranCompetition::with(['category', 'winners', 'media'])
            ->ordered()
            ->get();

        // إحصائيات
        $stats = [
            'total' => QuranCompetition::count(),
            'active' => QuranCompetition::where('is_active', true)->count(),
            'inactive' => QuranCompetition::where('is_active', false)->count(),
            'total_winners' => QuranCompetitionWinner::count(),
            'total_media' => QuranCompetitionMedia::count(),
            'with_category' => QuranCompetition::whereNotNull('category_id')->count(),
            'without_category' => QuranCompetition::whereNull('category_id')->count(),
        ];

        return view('dashboard.quran-competitions.index', compact('competitions', 'stats'));
    }

    /**
     * عرض نموذج إضافة مسابقة جديدة
     */
    public function create(): View
    {
        // جلب الفئات التي لديها مسابقات قرآن (أو الفئات الفرعية التي لديها مسابقات)
        $categories = Category::whereHas('quranCompetitions', function($q) {
            $q->where('is_active', true);
        })
        ->orWhereHas('children.quranCompetitions', function($q) {
            $q->where('is_active', true);
        })
        ->ordered()
        ->active()
        ->get();

        // جلب قائمة الأشخاص لإدارة القائمين على البرنامج
        $persons = \App\Models\Person::orderBy('first_name')->orderBy('last_name')->get();

        return view('dashboard.quran-competitions.create', compact('categories', 'persons'));
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
            'category_id' => 'nullable|exists:categories,id',
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
        $quranCompetition->load(['winners.person', 'media', 'sections.people']);
        $persons = Person::orderBy('first_name')->get();

        return view('dashboard.quran-competitions.show', compact('quranCompetition', 'persons'));
    }

    /**
     * عرض نموذج تعديل مسابقة
     */
    public function edit(QuranCompetition $quranCompetition): View
    {
        // جلب الفئات التي لديها مسابقات قرآن (أو الفئات الفرعية التي لديها مسابقات)
        // بالإضافة إلى الفئة الحالية للمسابقة (إن وجدت) حتى لو لم تعد لديها مسابقات
        $categories = Category::where(function($q) use ($quranCompetition) {
            $q->whereHas('quranCompetitions', function($query) {
                $query->where('is_active', true);
            })
            ->orWhereHas('children.quranCompetitions', function($query) {
                $query->where('is_active', true);
            });

            // إضافة الفئة الحالية للمسابقة إن وجدت
            if ($quranCompetition->category_id) {
                $q->orWhere('id', $quranCompetition->category_id);
            }
        })
        ->ordered()
        ->active()
        ->get();

        // جلب قائمة الأشخاص لإدارة القائمين على البرنامج
        $persons = Person::orderBy('first_name')->orderBy('last_name')->get();

        return view('dashboard.quran-competitions.edit', compact('quranCompetition', 'categories', 'persons'));
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
            'category_id' => 'nullable|exists:categories,id',
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

    /**
     * إنشاء قسم جديد داخل المسابقة
     */
    public function storeSection(Request $request, QuranCompetition $quranCompetition): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'اسم القسم مطلوب',
        ]);

        $quranCompetition->sections()->create([
            'name' => $validated['name'],
            'sort_order' => (int)($validated['sort_order'] ?? 0),
        ]);

        return back()->with('success', 'تم إنشاء القسم بنجاح');
    }

    /**
     * تحديث قسم
     */
    public function updateSection(Request $request, QuranCompetitionSection $section): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'اسم القسم مطلوب',
        ]);

        $section->update([
            'name' => $validated['name'],
            'sort_order' => (int)($validated['sort_order'] ?? 0),
        ]);

        return back()->with('success', 'تم تحديث القسم بنجاح');
    }

    /**
     * حذف قسم
     */
    public function destroySection(QuranCompetitionSection $section): RedirectResponse
    {
        $section->delete();
        return back()->with('success', 'تم حذف القسم بنجاح');
    }

    /**
     * إضافة أشخاص لقسم (دفعة واحدة)
     */
    public function attachSectionPeople(Request $request, QuranCompetitionSection $section): RedirectResponse
    {
        $data = $request->validate([
            'people' => ['required', 'array', 'min:1'],
            'people.*' => ['integer', 'exists:persons,id'],
        ], [
            'people.required' => 'يجب اختيار شخص واحد على الأقل',
        ]);

        $maxSort = (int) DB::table('quran_competition_section_person')
            ->where('section_id', $section->id)
            ->max('sort_order');

        $payload = [];
        $i = 1;
        foreach (collect($data['people'])->unique()->values() as $personId) {
            $payload[(int) $personId] = ['sort_order' => $maxSort + $i];
            $i++;
        }

        $section->people()->syncWithoutDetaching($payload);

        return back()->with('success', 'تمت إضافة الأشخاص للقسم بنجاح');
    }

    /**
     * إزالة شخص من قسم
     */
    public function detachSectionPerson(QuranCompetitionSection $section, Person $person): RedirectResponse
    {
        $section->people()->detach($person->id);
        return back()->with('success', 'تمت إزالة الشخص من القسم');
    }
}

