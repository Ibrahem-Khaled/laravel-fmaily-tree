<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductSubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        
        $query = ProductSubcategory::with(['category'])
            ->withCount('products');
        
        if ($categoryId) {
            $query->where('product_category_id', $categoryId);
        }
        
        $subcategories = $query->ordered()->get();
        $categories = ProductCategory::active()->ordered()->get();
        
        $stats = [
            'total' => ProductSubcategory::count(),
            'active' => ProductSubcategory::active()->count(),
            'inactive' => ProductSubcategory::where('is_active', false)->count(),
        ];
        
        return view('dashboard.products.subcategories.index', compact('subcategories', 'categories', 'categoryId', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'product_category_id' => 'required|exists:product_categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.subcategories.index', ['category_id' => $request->product_category_id])
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'description', 'product_category_id', 'is_active']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-subcategories', 'public');
        }

        $lastOrder = ProductSubcategory::where('product_category_id', $data['product_category_id'])
            ->max('sort_order') ?? 0;
        $data['sort_order'] = $lastOrder + 1;
        $data['is_active'] = $request->has('is_active') ? true : false;

        ProductSubcategory::create($data);

        return redirect()->route('products.subcategories.index', ['category_id' => $data['product_category_id']])
            ->with('success', 'تم إضافة الفئة الفرعية بنجاح');
    }

    public function update(Request $request, ProductSubcategory $subcategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'product_category_id' => 'required|exists:product_categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.subcategories.index', ['category_id' => $subcategory->product_category_id])
                ->withErrors($validator)
                ->withInput()
                ->with('edit_subcategory_id', $subcategory->id);
        }

        $data = $request->only(['name', 'description', 'product_category_id', 'is_active']);
        
        if ($request->hasFile('image')) {
            if ($subcategory->image) {
                Storage::disk('public')->delete($subcategory->image);
            }
            $data['image'] = $request->file('image')->store('product-subcategories', 'public');
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        $subcategory->update($data);

        return redirect()->route('products.subcategories.index', ['category_id' => $data['product_category_id']])
            ->with('success', 'تم تحديث الفئة الفرعية بنجاح');
    }

    public function destroy(ProductSubcategory $subcategory)
    {
        if ($subcategory->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف الفئة الفرعية لوجود منتجات مرتبطة بها.');
        }

        if ($subcategory->image) {
            Storage::disk('public')->delete($subcategory->image);
        }

        $categoryId = $subcategory->product_category_id;
        $subcategory->delete();

        return redirect()->route('products.subcategories.index', ['category_id' => $categoryId])
            ->with('success', 'تم حذف الفئة الفرعية بنجاح');
    }

    public function toggle(ProductSubcategory $subcategory)
    {
        $subcategory->update(['is_active' => !$subcategory->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $subcategory->is_active,
            'message' => $subcategory->is_active ? 'تم تفعيل الفئة الفرعية' : 'تم إلغاء تفعيل الفئة الفرعية'
        ]);
    }

    /**
     * جلب الفئات الفرعية حسب الفئة الرئيسية (AJAX)
     */
    public function getByCategory($category)
    {
        // التحقق من أن الفئة موجودة
        $categoryModel = ProductCategory::find($category);
        
        if (!$categoryModel) {
            return response()->json([
                'success' => false,
                'message' => 'الفئة غير موجودة',
                'subcategories' => []
            ], 404);
        }
        
        $subcategories = ProductSubcategory::where('product_category_id', $categoryModel->id)
            ->ordered()
            ->get(['id', 'name', 'product_category_id']);
        
        return response()->json([
            'success' => true,
            'subcategories' => $subcategories
        ]);
    }
}
