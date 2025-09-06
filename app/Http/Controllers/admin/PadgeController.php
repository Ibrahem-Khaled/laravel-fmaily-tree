<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Padge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PadgeController extends Controller
{
    /**
     * عرض قائمة الشارات مع الإحصائيات والبحث.
     */
    public function index(Request $request)
    {
        $query = Padge::query()->orderBy('sort_order', 'asc');

        // تطبيق البحث
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $padges = $query->paginate(10)->withQueryString();

        // حساب الإحصائيات
        $padgesCount = Padge::count();
        $activePadgesCount = Padge::where('is_active', true)->count();
        $inactivePadgesCount = $padgesCount - $activePadgesCount;

        return view('dashboard.padges.index', compact(
            'padges',
            'padgesCount',
            'activePadgesCount',
            'inactivePadgesCount'
        ));
    }

    /**
     * تخزين شارة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:padges,name',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'color' => 'nullable|string|max:7', // e.g., #RRGGBB
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'البيانات المدخلة غير صحيحة، يرجى المحاولة مرة أخرى.');
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active');

        // معالجة رفع الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('padges', 'public');
        }

        Padge::create($data);

        return redirect()->route('padges.index')->with('success', 'تمت إضافة الشارة بنجاح.');
    }

    /**
     * تحديث بيانات شارة محددة.
     */
    public function update(Request $request, Padge $padge)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:padges,name,' . $padge->id,
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'البيانات المدخلة غير صحيحة، يرجى المحاولة مرة أخرى.');
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active');

        // معالجة تحديث الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($padge->image) {
                Storage::disk('public')->delete($padge->image);
            }
            $data['image'] = $request->file('image')->store('padges', 'public');
        }

        $padge->update($data);

        return redirect()->route('padges.index')->with('success', 'تم تحديث الشارة بنجاح.');
    }

    /**
     * حذف شارة محددة من قاعدة البيانات.
     */
    public function destroy(Padge $padge)
    {
        // حذف الصورة المرتبطة بالشارة
        if ($padge->image) {
            Storage::disk('public')->delete($padge->image);
        }

        $padge->delete();

        return redirect()->route('padges.index')->with('success', 'تم حذف الشارة بنجاح.');
    }
}
