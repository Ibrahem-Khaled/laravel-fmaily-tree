<?php

namespace App\Http\Controllers;

use App\Models\FamilyCouncil;

class FamilyCouncilPublicController extends Controller
{
    /**
     * عرض صفحة جميع المجالس
     */
    public function index()
    {
        $councils = FamilyCouncil::getActiveCouncils();

        return view('councils.index', [
            'councils' => $councils,
        ]);
    }

    /**
     * عرض صفحة مجلس محدد
     */
    public function show(FamilyCouncil $council)
    {
        abort_unless($council->is_active, 404);

        $council->load('images');

        return view('councils.show', [
            'council' => $council,
        ]);
    }
}
