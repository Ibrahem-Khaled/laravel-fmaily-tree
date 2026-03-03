<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sponsors = Sponsor::withCount('competitions')->latest()->paginate(20);
        return view('dashboard.sponsors.index', compact('sponsors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.sponsors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sponsors', 'public');
        }

        Sponsor::create($validated);

        return redirect()->route('dashboard.sponsors.index')
            ->with('success', 'تم إضافة الراعي بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sponsor $sponsor)
    {
        return view('dashboard.sponsors.show', compact('sponsor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sponsor $sponsor)
    {
        return view('dashboard.sponsors.edit', compact('sponsor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($sponsor->image) {
                Storage::disk('public')->delete($sponsor->image);
            }
            $validated['image'] = $request->file('image')->store('sponsors', 'public');
        }

        $sponsor->update($validated);

        return redirect()->route('dashboard.sponsors.index')
            ->with('success', 'تم تحديث بيانات الراعي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->image) {
            Storage::disk('public')->delete($sponsor->image);
        }
        
        $sponsor->delete();

        return redirect()->route('dashboard.sponsors.index')
            ->with('success', 'تم حذف الراعي بنجاح');
    }
}
