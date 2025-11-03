<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class SiteContentController extends Controller
{
    /**
     * عرض صفحة إدارة المحتوى
     */
    public function index()
    {
        $familyBrief = SiteContent::firstOrNew(['key' => 'family_brief']);
        $whatsNew = SiteContent::firstOrNew(['key' => 'whats_new']);
        
        return view('dashboard.site-content.index', compact('familyBrief', 'whatsNew'));
    }

    /**
     * تحديث نبذة العائلة
     */
    public function updateFamilyBrief(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'family_brief'],
            [
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? true : false
            ]
        );

        return redirect()->route('dashboard.site-content.index')
            ->with('success', 'تم تحديث نبذة العائلة بنجاح');
    }

    /**
     * تحديث قسم ما الجديد
     */
    public function updateWhatsNew(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'whats_new'],
            [
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? true : false
            ]
        );

        return redirect()->route('dashboard.site-content.index')
            ->with('success', 'تم تحديث قسم ما الجديد بنجاح');
    }
}
