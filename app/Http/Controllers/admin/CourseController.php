<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * عرض صفحة إدارة الدورات
     */
    public function index()
    {
        $courses = Course::orderBy('order')->get();
        
        return view('dashboard.courses.index', compact('courses'));
    }

    /**
     * إضافة دورة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'instructor' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'students' => 'nullable|integer|min:0',
            'link' => 'nullable|url|max:500',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        $lastOrder = Course::max('order') ?? 0;

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'instructor' => $request->instructor,
            'duration' => $request->duration,
            'students' => $request->students ?? 0,
            'link' => $request->link,
            'order' => $lastOrder + 1,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'تم إضافة الدورة بنجاح');
    }

    /**
     * تحديث دورة
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'instructor' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'students' => 'nullable|integer|min:0',
            'link' => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }
            
            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image_path = $imagePath;
        }

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'instructor' => $request->instructor,
            'duration' => $request->duration,
            'students' => $request->students ?? 0,
            'link' => $request->link,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'تم تحديث الدورة بنجاح');
    }

    /**
     * جلب بيانات دورة (للتعديل)
     */
    public function show(Course $course)
    {
        return response()->json([
            'title' => $course->title,
            'description' => $course->description,
            'instructor' => $course->instructor,
            'duration' => $course->duration,
            'students' => $course->students,
            'link' => $course->link,
            'image_url' => $course->image_url,
            'is_active' => $course->is_active,
        ]);
    }

    /**
     * حذف دورة
     */
    public function destroy(Course $course)
    {
        if ($course->image_path) {
            Storage::disk('public')->delete($course->image_path);
        }
        
        $course->delete();

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'تم حذف الدورة بنجاح');
    }

    /**
     * إعادة ترتيب الدورات
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:courses,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $courseId) {
                Course::where('id', $courseId)
                    ->update(['order' => $order + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'تم إعادة الترتيب بنجاح']);
    }

    /**
     * تفعيل/تعطيل دورة
     */
    public function toggle(Course $course)
    {
        $course->update([
            'is_active' => !$course->is_active
        ]);

        return redirect()->route('dashboard.courses.index')
            ->with('success', 'تم تحديث حالة الدورة بنجاح');
    }
}
