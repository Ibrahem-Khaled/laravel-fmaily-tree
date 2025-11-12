<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyCouncil;
use App\Models\CouncilImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FamilyCouncilController extends Controller
{
    /**
     * عرض صفحة إدارة مجالس العائلة
     */
    public function index()
    {
        $councils = FamilyCouncil::orderBy('display_order')->get();

        // إحصائيات المجالس
        $stats = [
            'total' => FamilyCouncil::count(),
            'active' => FamilyCouncil::where('is_active', true)->count(),
            'inactive' => FamilyCouncil::where('is_active', false)->count(),
            'with_images' => FamilyCouncil::whereHas('images')->count(),
            'with_map' => FamilyCouncil::whereNotNull('google_map_url')->count(),
        ];

        return view('dashboard.councils.index', compact('councils', 'stats'));
    }

    /**
     * إضافة مجلس جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'address' => 'nullable|string|max:500',
            'google_map_url' => 'nullable|url|max:1000',
            'working_days_from' => 'nullable|string|max:50',
            'working_days_to' => 'nullable|string|max:50',
        ]);

        $lastOrder = FamilyCouncil::max('display_order') ?? 0;

        FamilyCouncil::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'google_map_url' => $request->google_map_url,
            'working_days_from' => $request->working_days_from,
            'working_days_to' => $request->working_days_to,
            'display_order' => $lastOrder + 1,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('dashboard.councils.index')
            ->with('success', 'تم إضافة المجلس بنجاح');
    }

    /**
     * تحديث مجلس
     */
    public function update(Request $request, FamilyCouncil $council)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'address' => 'nullable|string|max:500',
            'google_map_url' => 'nullable|url|max:1000',
            'working_days_from' => 'nullable|string|max:50',
            'working_days_to' => 'nullable|string|max:50',
        ]);

        $council->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'google_map_url' => $request->google_map_url,
            'working_days_from' => $request->working_days_from,
            'working_days_to' => $request->working_days_to,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('dashboard.councils.index')
            ->with('success', 'تم تحديث المجلس بنجاح');
    }

    /**
     * جلب بيانات مجلس (للتعديل)
     */
    public function show(FamilyCouncil $council)
    {
        return response()->json([
            'name' => $council->name,
            'description' => $council->description,
            'address' => $council->address,
            'google_map_url' => $council->google_map_url,
            'working_days_from' => $council->working_days_from,
            'working_days_to' => $council->working_days_to,
            'is_active' => $council->is_active,
        ]);
    }

    /**
     * حذف مجلس
     */
    public function destroy(FamilyCouncil $council)
    {
        // حذف الصور المرتبطة
        $council->images->each(function (CouncilImage $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
            $image->delete();
        });

        $council->delete();

        return redirect()->route('dashboard.councils.index')
            ->with('success', 'تم حذف المجلس بنجاح');
    }

    /**
     * إعادة ترتيب المجالس
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:family_councils,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $councilId) {
                FamilyCouncil::where('id', $councilId)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * صفحة إدارة تفاصيل مجلس محدد
     */
    public function manage(FamilyCouncil $council)
    {
        $council->load('images');

        return view('dashboard.councils.manage', [
            'council' => $council,
        ]);
    }

    /**
     * إضافة صورة للمجلس
     */
    public function storeImage(Request $request, FamilyCouncil $council)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string|max:500',
        ]);

        $imagePath = $request->file('image')->store('councils/images', 'public');

        $nextOrder = ($council->images()->max('display_order') ?? 0) + 1;

        $council->images()->create([
            'image_path' => $imagePath,
            'description' => $request->description,
            'display_order' => $nextOrder,
        ]);

        return redirect()
            ->route('dashboard.councils.manage', $council)
            ->with('success', 'تم إضافة الصورة بنجاح');
    }

    /**
     * حذف صورة من المجلس
     */
    public function destroyImage(FamilyCouncil $council, CouncilImage $image)
    {
        abort_unless($image->council_id === $council->id, 404);

        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }
        if ($image->thumbnail_path) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }

        $image->delete();

        return redirect()
            ->route('dashboard.councils.manage', $council)
            ->with('success', 'تم حذف الصورة بنجاح');
    }

    /**
     * إعادة ترتيب صور المجلس
     */
    public function reorderImages(Request $request, FamilyCouncil $council)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:council_images,id',
        ]);

        DB::transaction(function () use ($request, $council) {
            foreach ($request->orders as $order => $imageId) {
                CouncilImage::where('id', $imageId)
                    ->where('council_id', $council->id)
                    ->update(['display_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true]);
    }
}
