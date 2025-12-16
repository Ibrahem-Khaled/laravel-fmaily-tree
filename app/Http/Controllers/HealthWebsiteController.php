<?php

namespace App\Http\Controllers;

use App\Models\HealthWebsite;
use Illuminate\Http\Request;

class HealthWebsiteController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = HealthWebsite::active();

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $websites = $query->ordered()->paginate(12)->appends($request->query());

        // جلب الفئات المتاحة
        $categories = HealthWebsite::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('health-websites.index', compact('websites', 'category', 'search', 'categories'));
    }

    public function show(HealthWebsite $website)
    {
        if (!$website->is_active) {
            abort(404);
        }

        // مواقع مشابهة من نفس الفئة
        $relatedWebsites = HealthWebsite::active()
            ->where('id', '!=', $website->id)
            ->where('category', $website->category)
            ->ordered()
            ->limit(4)
            ->get();

        return view('health-websites.show', compact('website', 'relatedWebsites'));
    }
}
