<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RentalRequest;
use Illuminate\Http\Request;

class RentalRequestAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = RentalRequest::with(['product', 'product.category', 'user']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        $stats = [
            'total' => RentalRequest::count(),
            'pending' => RentalRequest::pending()->count(),
            'approved' => RentalRequest::approved()->count(),
            'rejected' => RentalRequest::rejected()->count(),
            'completed' => RentalRequest::completed()->count(),
        ];

        return view('dashboard.rental-requests.index', compact('requests', 'status', 'search', 'stats'));
    }

    public function show(RentalRequest $request)
    {
        $request->load(['product', 'product.category', 'product.subcategory', 'product.owner', 'product.location', 'user']);

        return view('dashboard.rental-requests.show', compact('request'));
    }

    public function updateStatus(Request $request, RentalRequest $rentalRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $rentalRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
