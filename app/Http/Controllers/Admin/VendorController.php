<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorProfile;
use App\Models\MarketplaceListing;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = VendorProfile::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show(VendorProfile $vendor)
    {
        $vendor->load(['user', 'listings', 'earnings']);
        
        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(VendorProfile $vendor)
    {
        $vendor->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Vendor approved successfully');
    }

    public function reject(Request $request, VendorProfile $vendor)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $vendor->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'],
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Vendor rejected');
    }

    public function suspend(VendorProfile $vendor)
    {
        $vendor->update(['status' => 'suspended']);

        return back()->with('success', 'Vendor suspended');
    }

    public function activate(VendorProfile $vendor)
    {
        $vendor->update(['status' => 'approved']);

        return back()->with('success', 'Vendor activated');
    }

    public function listings(Request $request)
    {
        $query = MarketplaceListing::with(['vendor.user', 'category']);

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('search') . '%')
                  ->orWhere('description', 'like', '%' . $request->get('search') . '%');
            });
        }

        $listings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.vendors.listings', compact('listings'));
    }

    public function approveListing(MarketplaceListing $listing)
    {
        $listing->update([
            'status' => 'active',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Listing approved');
    }

    public function rejectListing(Request $request, MarketplaceListing $listing)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $listing->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Listing rejected');
    }
}
