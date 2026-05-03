<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\BreastfeedingPublicPageData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreastfeedingPublicController extends Controller
{
    public function __construct(
        protected BreastfeedingPublicPageData $breastfeedingPublicPageData
    ) {}

    /**
     * Display the public breastfeeding page with new layout
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $viewMode = $request->get('view_mode', 'mothers');

        $page = $this->breastfeedingPublicPageData->build($search);

        $mothersData = $page['mothers'];
        $childrenData = $page['children'];
        $stats = $page['stats'];

        return view('breastfeeding.public.index', compact('mothersData', 'childrenData', 'stats', 'search', 'viewMode'));
    }

    /**
     * Display breastfeeding details for a specific person - Coming Soon
     */
    public function show(Person $person): View
    {
        return view('breastfeeding.public.coming-soon');
    }
}
