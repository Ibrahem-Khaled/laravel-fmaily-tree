<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 15);

        $query = Location::query()
            ->withCount('persons')
            ->ordered();

        // البحث
        if ($search) {
            $query->search($search);
        }

        $locations = $query->paginate($perPage)->appends($request->query());

        // إحصائيات
        $stats = [
            'total' => Location::count(),
            'with_persons' => Location::where('persons_count', '>', 0)->count(),
            'empty' => Location::where('persons_count', 0)->count(),
        ];

        return view('dashboard.locations.index', compact('locations', 'search', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);

        // البحث عن أماكن مشابهة قبل الإنشاء
        $similar = Location::findSimilar($validated['name'], 0.9);
        
        if (!empty($similar) && $similar[0]['similarity'] >= 0.95) {
            return back()
                ->withInput()
                ->with('warning', 'يوجد مكان مشابه جداً: ' . $similar[0]['location']->name . ' (نسبة التشابه: ' . round($similar[0]['similarity'] * 100) . '%)')
                ->with('similar_locations', $similar);
        }

        $location = Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'تم إنشاء المكان بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        $location->load('persons');
        return view('dashboard.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('dashboard.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);

        // البحث عن أماكن مشابهة (باستثناء المكان الحالي)
        $similar = Location::findSimilar($validated['name'], 0.9);
        $similar = array_filter($similar, function($item) use ($location) {
            return $item['location']->id !== $location->id;
        });

        if (!empty($similar) && $similar[0]['similarity'] >= 0.95) {
            return back()
                ->withInput()
                ->with('warning', 'يوجد مكان مشابه جداً: ' . $similar[0]['location']->name . ' (نسبة التشابه: ' . round($similar[0]['similarity'] * 100) . '%)')
                ->with('similar_locations', $similar);
        }

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'تم تحديث المكان بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        // التحقق من وجود أشخاص مرتبطين
        if ($location->persons()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المكان لوجود أشخاص مرتبطين به.');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'تم حذف المكان بنجاح.');
    }

    /**
     * دمج أماكن متعددة في مكان واحد
     */
    public function merge(Request $request)
    {
        $validated = $request->validate([
            'target_location_id' => 'required|exists:locations,id',
            'source_location_ids' => 'required|array',
            'source_location_ids.*' => 'exists:locations,id',
        ]);

        $targetLocation = Location::findOrFail($validated['target_location_id']);
        $sourceLocations = Location::whereIn('id', $validated['source_location_ids'])->get();

        $mergedCount = 0;

        foreach ($sourceLocations as $sourceLocation) {
            if ($sourceLocation->id === $targetLocation->id) {
                continue; // تخطي المكان المستهدف
            }

            // نقل جميع الأشخاص من المكان المصدر إلى المكان المستهدف
            Person::where('location_id', $sourceLocation->id)
                ->update(['location_id' => $targetLocation->id]);

            $mergedCount += $sourceLocation->persons_count;

            // حذف المكان المصدر
            $sourceLocation->delete();
        }

        // تحديث عداد الأشخاص للمكان المستهدف
        $targetLocation->refresh();
        $targetLocation->persons_count = $targetLocation->persons()->count();
        $targetLocation->save();

        return redirect()->route('locations.index')
            ->with('success', "تم دمج {$mergedCount} مكان في المكان المستهدف بنجاح.");
    }

    /**
     * البحث عن أماكن مشابهة (AJAX)
     */
    public function findSimilar(Request $request): JsonResponse
    {
        $name = $request->input('name');
        
        if (!$name) {
            return response()->json(['similar' => []]);
        }

        $similar = Location::findSimilar($name, 0.8);
        
        return response()->json([
            'similar' => array_map(function($item) {
                return [
                    'id' => $item['location']->id,
                    'name' => $item['location']->display_name, // استخدام display_name
                    'similarity' => round($item['similarity'] * 100, 1),
                    'persons_count' => $item['location']->persons_count,
                ];
            }, $similar)
        ]);
    }

    /**
     * البحث التلقائي (Autocomplete) للأماكن
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $locations = Location::where('name', 'like', "%{$query}%")
            ->orWhere('normalized_name', 'like', "%" . Location::normalizeName($query) . "%")
            ->orderBy('persons_count', 'desc')
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json(
            $locations->map(function($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->display_name, // استخدام display_name بدلاً من name
                    'persons_count' => $location->persons_count,
                ];
            })
        );
    }
}
