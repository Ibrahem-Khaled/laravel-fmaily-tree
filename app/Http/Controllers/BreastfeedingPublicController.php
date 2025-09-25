<?php

namespace App\Http\Controllers;

use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreastfeedingPublicController extends Controller
{
    /**
     * Display the public breastfeeding page - Coming Soon
     */
    public function index(Request $request): View
    {
        // عرض صفحة "قريباً" بدلاً من المحتوى الحالي
        return view('breastfeeding.public.coming-soon');
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
