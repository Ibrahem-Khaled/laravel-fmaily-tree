<?php

namespace App\Http\Controllers;

use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreastfeedingPublicController extends Controller
{
    /**
     * Display the public breastfeeding page
     */
    public function index(Request $request): View
    {
        $query = Breastfeeding::with(['nursingMother', 'breastfedChild'])
            ->where('is_active', true);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('nursingMother', function($subQ) use ($searchTerm) {
                    $subQ->where('first_name', 'like', '%' . $searchTerm . '%')
                         ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                })->orWhereHas('breastfedChild', function($subQ) use ($searchTerm) {
                    $subQ->where('first_name', 'like', '%' . $searchTerm . '%')
                         ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        $breastfeedings = $query->orderBy('start_date', 'desc')->get();

        // Group breastfeeding relationships by nursing mother
        $nursingMothers = $breastfeedings->groupBy('nursing_mother_id');

        // Get statistics
        $stats = [
            'total_relationships' => $breastfeedings->count(),
            'total_nursing_mothers' => $nursingMothers->count(),
            'total_breastfed_children' => $breastfeedings->pluck('breastfed_child_id')->unique()->count(),
            'active_breastfeeding' => $breastfeedings->where('end_date', null)->count(),
            'completed_breastfeeding' => $breastfeedings->where('end_date', '!=', null)->count(),
        ];

        return view('breastfeeding.public.index', compact('breastfeedings', 'nursingMothers', 'stats'));
    }

    /**
     * Display breastfeeding details for a specific person
     */
    public function show(Person $person): View
    {
        // Get nursing relationships where this person is the nursing mother
        $nursingRelationships = $person->nursingRelationships()
            ->with('breastfedChild')
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->get();

        // Get breastfeeding relationships where this person is the breastfed child
        $breastfedRelationships = $person->breastfedRelationships()
            ->with('nursingMother')
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('breastfeeding.public.show', compact('person', 'nursingRelationships', 'breastfedRelationships'));
    }
}
