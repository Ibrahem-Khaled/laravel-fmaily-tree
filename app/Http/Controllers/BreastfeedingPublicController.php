<?php

namespace App\Http\Controllers;

use App\Models\Breastfeeding;
use App\Models\Person;
use App\Models\Marriage;
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
        $viewMode = $request->get('view_mode', 'mothers'); // 'mothers' or 'children'

        // جلب الأمهات المرضعات مع أطفالهم والأب من الرضاعة
        $nursingMothersQuery = Person::where('gender', 'female')
            ->whereHas('nursingRelationships')
            ->with(['nursingRelationships.breastfedChild', 'nursingRelationships.breastfeedingFather']);

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

        // تنظيم البيانات حسب الأمهات
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
                        'relationship_id' => $relationship->id,
                        'breastfeeding_father' => $relationship->breastfeedingFather ? [
                            'id' => $relationship->breastfeedingFather->id,
                            'name' => $relationship->breastfeedingFather->full_name,
                            'first_name' => $relationship->breastfeedingFather->first_name,
                            'avatar' => $relationship->breastfeedingFather->avatar,
                        ] : null
                    ];
                })
            ];
        });

        // تنظيم البيانات حسب الأطفال المرتضعين
        $childrenData = collect();
        foreach ($nursingMothers as $mother) {
            foreach ($mother->nursingRelationships as $relationship) {
                $childrenData->push([
                    'id' => $relationship->breastfedChild->id,
                    'name' => $relationship->breastfedChild->full_name,
                    'first_name' => $relationship->breastfedChild->first_name,
                    'avatar' => $relationship->breastfedChild->avatar,
                    'start_date' => $relationship->start_date?->format('Y/m/d'),
                    'end_date' => $relationship->end_date?->format('Y/m/d'),
                    'duration_months' => $relationship->duration_in_months,
                    'is_active' => $relationship->is_active,
                    'notes' => $relationship->notes,
                    'relationship_id' => $relationship->id,
                    'nursing_mother' => [
                        'id' => $mother->id,
                        'name' => $mother->full_name,
                        'first_name' => $mother->first_name,
                        'avatar' => $mother->avatar,
                    ],
                    'breastfeeding_father' => $relationship->breastfeedingFather ? [
                        'id' => $relationship->breastfeedingFather->id,
                        'name' => $relationship->breastfeedingFather->full_name,
                        'first_name' => $relationship->breastfeedingFather->first_name,
                        'avatar' => $relationship->breastfeedingFather->avatar,
                    ] : null
                ]);
            }
        }

        // ترتيب الأطفال حسب الاسم
        $childrenData = $childrenData->sortBy('first_name')->values();

        // الإحصائيات
        $stats = [
            'total_relationships' => Breastfeeding::count(),
            'total_nursing_mothers' => Person::where('gender', 'female')->whereHas('nursingRelationships')->count(),
            'total_breastfed_children' => Person::whereHas('breastfedRelationships')->count(),
            'active_breastfeeding' => Breastfeeding::where('is_active', true)->count(),
        ];

        return view('breastfeeding.public.index', compact('mothersData', 'childrenData', 'stats', 'search', 'viewMode'));
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
