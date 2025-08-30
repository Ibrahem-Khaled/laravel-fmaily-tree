<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuickStoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    // دالة مخصصة لاستقبال طلبات إنشاء الفئات عبر AJAX
    public function store(QuickStoreCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        // رفع صورة اختيارية
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($data);

        // نُرجع الفئة الجديدة كي نضيفها للقائمة (حتى لو لا تملك مقالات بعد)
        return response()->json([
            'ok' => true,
            'category' => [
                'id'          => $category->id,
                'name'        => $category->name,
                'description' => $category->description,
                'image'       => $category->image ? asset('storage/' . $category->image) : null,
            ]
        ]);
    }
}
