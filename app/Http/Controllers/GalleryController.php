<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Person;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * عرض صفحة معرض الصور مع إمكانية الفلترة.
     */
    public function index(Request $request)
    {
        // الاستعلام الأساسي لجلب الصور مع علاقاتها لتجنب مشكلة N+1
        $query = Image::with(['article.category', 'article.person']);

        // 1. الفلترة بناءً على الفئة (Category)
        $query->when($request->category, function ($q, $categoryId) {
            return $q->whereHas('article', function ($subQ) use ($categoryId) {
                $subQ->where('category_id', $categoryId);
            });
        });

        // 2. الفلترة بناءً على الشخص المساهم (Author)
        $query->when($request->person, function ($q, $personId) {
            return $q->whereHas('article', function ($subQ) use ($personId) {
                $subQ->where('person_id', $personId);
            });
        });

        // جلب الصور مع الترتيب من الأحدث للأقدم + الترقيم (Pagination)
        $images = $query->latest()->paginate(24);

        // جلب الفئات الرئيسية مع الفئات الفرعية التابعة لها
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // جلب الأشخاص الذين لديهم مقالات فقط لعرضهم في قائمة الفلترة
        $authors = Person::whereHas('articles')->get();

        // إرسال كل البيانات إلى الـ View
        return view('gallery', [
            'images' => $images,
            'categories' => $categories,
            'authors' => $authors,
            'currentCategory' => $request->category, // لإظهار الفلتر الحالي
            'currentAuthor' => $request->person,     // لإظهار الفلتر الحالي
        ]);
    }
}
