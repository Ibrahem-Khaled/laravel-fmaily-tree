<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;

class ProductStoreController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category');
        $subcategoryId = $request->query('subcategory');
        $personId = $request->query('person');
        $search = $request->query('search');
        
        $query = Product::with(['category', 'subcategory', 'owner', 'location'])->active();
        
        if ($categoryId) {
            $query->where('product_category_id', $categoryId);
        }
        
        if ($subcategoryId) {
            $query->where('product_subcategory_id', $subcategoryId);
        }
        
        if ($personId) {
            $query->where('owner_id', $personId);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $query->ordered()->paginate(12)->appends($request->query());
        $categories = ProductCategory::active()->withCount('products')->ordered()->get();
        
        // جلب الأشخاص أصحاب المنتجات مع عدد منتجات كل شخص
        $productOwners = \App\Models\Person::whereHas('products', function($q) {
            $q->active();
        })
        ->withCount(['products' => function($q) {
            $q->active();
        }])
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();
        
        return view('store.index', compact('products', 'categories', 'categoryId', 'subcategoryId', 'personId', 'search', 'productOwners'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subcategory', 'owner', 'location']);
        
        // منتجات مشابهة من نفس الفئة
        $relatedProducts = Product::with(['category', 'subcategory', 'owner', 'location'])
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->ordered()
            ->limit(4)
            ->get();
        
        return view('store.show', compact('product', 'relatedProducts'));
    }

    public function category(ProductCategory $category)
    {
        $category->load(['subcategories' => function($q) {
            $q->active()->ordered();
        }]);
        
        $products = Product::with(['category', 'subcategory', 'owner', 'location'])
            ->where('product_category_id', $category->id)
            ->active()
            ->ordered()
            ->paginate(12);
        
        return view('store.category', compact('category', 'products'));
    }

    public function subcategory(ProductSubcategory $subcategory)
    {
        $subcategory->load('category');
        
        $products = Product::with(['category', 'subcategory', 'owner', 'location'])
            ->where('product_subcategory_id', $subcategory->id)
            ->active()
            ->ordered()
            ->paginate(12);
        
        return view('store.subcategory', compact('subcategory', 'products'));
    }
}
