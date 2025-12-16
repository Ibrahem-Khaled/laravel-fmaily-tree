<?php

namespace App\Http\Controllers;

use App\Models\RentalRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalRequestController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // التأكد من أن المنتج مؤجر ونشط
        if (!$product->is_rental || !$product->is_active) {
            return back()->with('error', 'هذا المنتج غير متاح للاستعارة.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        RentalRequest::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال طلب الاستعارة بنجاح. سيتم مراجعته قريباً.');
    }

    public function index()
    {
        $requests = RentalRequest::with(['product', 'product.category', 'product.owner'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rental-requests.index', compact('requests'));
    }

    public function show(RentalRequest $request)
    {
        // التأكد من أن المستخدم يملك هذا الطلب
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        $request->load(['product', 'product.category', 'product.subcategory', 'product.owner', 'product.location', 'user']);

        return view('rental-requests.show', compact('request'));
    }
}
