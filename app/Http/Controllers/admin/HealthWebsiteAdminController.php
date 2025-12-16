<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HealthWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HealthWebsiteAdminController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = HealthWebsite::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        $websites = $query->ordered()->paginate(20)->appends($request->query());

        $stats = [
            'total' => HealthWebsite::count(),
            'active' => HealthWebsite::active()->count(),
            'inactive' => HealthWebsite::where('is_active', false)->count(),
        ];

        // جلب الفئات المتاحة
        $categories = HealthWebsite::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('dashboard.health-websites.index', compact('websites', 'category', 'search', 'stats', 'categories'));
    }

    public function create()
    {
        return view('dashboard.health-websites.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'url', 'description', 'category', 'is_active']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('health-websites', 'public');
        }

        $lastOrder = HealthWebsite::max('sort_order') ?? 0;
        $data['sort_order'] = $lastOrder + 1;
        $data['is_active'] = $request->input('is_active', 0) == 1;

        HealthWebsite::create($data);

        return redirect()->route('dashboard.health-websites.index')
            ->with('success', 'تم إضافة الموقع بنجاح');
    }

    public function edit(HealthWebsite $healthWebsite)
    {
        return view('dashboard.health-websites.edit', compact('healthWebsite'));
    }

    public function update(Request $request, HealthWebsite $healthWebsite)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'url', 'description', 'category', 'is_active']);

        if ($request->hasFile('logo')) {
            if ($healthWebsite->logo) {
                Storage::disk('public')->delete($healthWebsite->logo);
            }
            $data['logo'] = $request->file('logo')->store('health-websites', 'public');
        }

        $data['is_active'] = $request->input('is_active', 0) == 1;

        $healthWebsite->update($data);

        return redirect()->route('dashboard.health-websites.index')
            ->with('success', 'تم تحديث الموقع بنجاح');
    }

    public function destroy(HealthWebsite $healthWebsite)
    {
        if ($healthWebsite->logo) {
            Storage::disk('public')->delete($healthWebsite->logo);
        }

        $healthWebsite->delete();

        return redirect()->route('dashboard.health-websites.index')
            ->with('success', 'تم حذف الموقع بنجاح');
    }

    public function toggle(HealthWebsite $healthWebsite)
    {
        $healthWebsite->update(['is_active' => !$healthWebsite->is_active]);

        return back()->with('success', $healthWebsite->is_active ? 'تم تفعيل الموقع' : 'تم إلغاء تفعيل الموقع');
    }
}
