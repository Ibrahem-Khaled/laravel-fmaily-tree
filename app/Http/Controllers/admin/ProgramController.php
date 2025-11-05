<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    /**
     * عرض صفحة إدارة البرامج
     */
    public function index()
    {
        $programs = Image::where('is_program', true)
            ->orderBy('program_order')
            ->get();
        
        return view('dashboard.programs.index', compact('programs'));
    }

    /**
     * إضافة برنامج جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'program_title' => 'required|string|max:255',
            'program_description' => 'nullable|string|max:1000',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('programs', 'public');

        $lastOrder = Image::where('is_program', true)->max('program_order') ?? 0;

        Image::create([
            'name' => $request->name ?? $request->program_title,
            'path' => $imagePath,
            'program_title' => $request->program_title,
            'program_description' => $request->program_description,
            'program_order' => $lastOrder + 1,
            'is_program' => true,
            'media_type' => 'image',
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.programs.index')
            ->with('success', 'تم إضافة البرنامج بنجاح');
    }

    /**
     * تحديث برنامج
     */
    public function update(Request $request, Image $program)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'program_title' => 'required|string|max:255',
            'program_description' => 'nullable|string|max:1000',
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($program->path) {
                Storage::disk('public')->delete($program->path);
            }
            
            $imagePath = $request->file('image')->store('programs', 'public');
            $program->path = $imagePath;
        }

        $program->update([
            'name' => $request->name ?? $request->program_title,
            'program_title' => $request->program_title,
            'program_description' => $request->program_description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard.programs.index')
            ->with('success', 'تم تحديث البرنامج بنجاح');
    }

    /**
     * جلب بيانات برنامج (للتعديل)
     */
    public function show(Image $program)
    {
        return response()->json([
            'program_title' => $program->program_title,
            'program_description' => $program->program_description,
            'name' => $program->name,
            'image_url' => $program->path ? asset('storage/' . $program->path) : null,
            'category_id' => $program->category_id,
        ]);
    }

    /**
     * حذف برنامج
     */
    public function destroy(Image $program)
    {
        if ($program->path) {
            Storage::disk('public')->delete($program->path);
        }
        
        $program->update([
            'is_program' => false,
            'program_title' => null,
            'program_description' => null,
            'program_order' => 0,
        ]);

        return redirect()->route('dashboard.programs.index')
            ->with('success', 'تم حذف البرنامج بنجاح');
    }

    /**
     * إعادة ترتيب البرامج
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:images,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $programId) {
                Image::where('id', $programId)
                    ->where('is_program', true)
                    ->update(['program_order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }
}
