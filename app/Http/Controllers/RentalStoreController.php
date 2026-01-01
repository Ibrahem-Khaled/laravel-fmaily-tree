<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;

class RentalStoreController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category');
        $subcategoryId = $request->query('subcategory');
        $personId = $request->query('person');
        $search = $request->query('search');

        $query = Product::with(['category', 'subcategory', 'owner', 'location'])
            ->active()
            ->rental();

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
        $categories = ProductCategory::active()
            ->whereHas('products', function($q) {
                $q->active()->rental();
            })
            ->withCount(['products' => function($q) {
                $q->active()->rental();
            }])
            ->ordered()
            ->get();

        // جلب الأشخاص أصحاب المنتجات المؤجرة مع عدد منتجات كل شخص
        $productOwners = \App\Models\Person::whereHas('products', function($q) {
            $q->active()->rental();
        })
        ->withCount(['products' => function($q) {
            $q->active()->rental();
        }])
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

        return view('rental.index', compact('products', 'categories', 'categoryId', 'subcategoryId', 'personId', 'search', 'productOwners'));
    }

    public function show(Product $product)
    {
        // التأكد من أن المنتج مؤجر ونشط
        if (!$product->is_rental || !$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'subcategory', 'owner', 'location']);

        // منتجات مشابهة من نفس الفئة
        $relatedProducts = Product::with(['category', 'subcategory', 'owner', 'location'])
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->rental()
            ->ordered()
            ->limit(4)
            ->get();

        return view('rental.show', compact('product', 'relatedProducts'));
    }
}
