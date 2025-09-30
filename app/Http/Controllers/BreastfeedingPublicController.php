<?php

namespace App\Http\Controllers;

use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreastfeedingPublicController extends Controller
{
    /**
     * Display the public breastfeeding page with new layout
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');

        // جلب الأمهات المرضعات مع أطفالهم
        $nursingMothersQuery = Person::where('gender', 'female')
            ->whereHas('nursingRelationships')
            ->with(['nursingRelationships.breastfedChild']);

        if ($search) {
            $nursingMothersQuery->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhereHas('nursingRelationships.breastfedChild', function($childQuery) use ($search) {
                          $childQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                      });
            });
        }

        $nursingMothers = $nursingMothersQuery->get();

        // تنظيم البيانات
        $mothersData = $nursingMothers->map(function($mother) {
            return [
                'id' => $mother->id,
                'name' => $mother->full_name,
                'first_name' => $mother->first_name,
                'avatar' => $mother->avatar,
                'children' => $mother->nursingRelationships->map(function($relationship) {
                    return [
                        'id' => $relationship->breastfedChild->id,
                        'name' => $relationship->breastfedChild->full_name,
                        'first_name' => $relationship->breastfedChild->first_name,
                        'avatar' => $relationship->breastfedChild->avatar,
                        'start_date' => $relationship->start_date?->format('Y/m/d'),
                        'end_date' => $relationship->end_date?->format('Y/m/d'),
                        'duration_months' => $relationship->duration_in_months,
                        'is_active' => $relationship->is_active,
                        'notes' => $relationship->notes,
                        'relationship_id' => $relationship->id
                    ];
                })
            ];
        });

        // الإحصائيات
        $stats = [
            'total_relationships' => Breastfeeding::count(),
            'total_nursing_mothers' => Person::where('gender', 'female')->whereHas('nursingRelationships')->count(),
            'total_breastfed_children' => Person::whereHas('breastfedRelationships')->count(),
            'active_breastfeeding' => Breastfeeding::where('is_active', true)->count(),
        ];

        return view('breastfeeding.public.index', compact('mothersData', 'stats', 'search'));
    }

    /**
     * Display breastfeeding details for a specific person - Coming Soon
     */
    public function show(Person $person): View
    {
        // عرض صفحة "قريباً" بدلاً من المحتوى الحالي
        return view('breastfeeding.public.coming-soon');
    }
}
