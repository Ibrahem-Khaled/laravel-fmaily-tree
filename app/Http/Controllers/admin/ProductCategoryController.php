<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount(['products', 'subcategories'])
            ->ordered()
            ->get();
        
        $stats = [
            'total' => ProductCategory::count(),
            'active' => ProductCategory::active()->count(),
            'inactive' => ProductCategory::where('is_active', false)->count(),
        ];
        
        return view('dashboard.products.categories.index', compact('categories', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.categories.index')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'description', 'is_active']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-categories', 'public');
        }

        $lastOrder = ProductCategory::max('sort_order') ?? 0;
        $data['sort_order'] = $lastOrder + 1;
        $data['is_active'] = $request->has('is_active') ? true : false;

        ProductCategory::create($data);

        return redirect()->route('products.categories.index')
            ->with('success', 'تم إضافة الفئة بنجاح');
    }

    public function update(Request $request, ProductCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.categories.index')
                ->withErrors($validator)
                ->withInput()
                ->with('edit_category_id', $category->id);
        }

        $data = $request->only(['name', 'description', 'is_active']);
        
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('product-categories', 'public');
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        $category->update($data);

        return redirect()->route('products.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->products()->exists() || $category->subcategories()->exists()) {
            return back()->with('error', 'لا يمكن حذف الفئة لوجود منتجات أو فئات فرعية مرتبطة بها.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('products.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:product_categories,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $item) {
            ProductCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'تم تحديث الترتيب بنجاح']);
    }

    public function toggle(ProductCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'تم تفعيل الفئة' : 'تم إلغاء تفعيل الفئة'
        ]);
    }
}
