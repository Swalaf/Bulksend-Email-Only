<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpAccount;
use Illuminate\Http\Request;

class SmtpListingController extends Controller
{
    public function index(Request $request)
    {
        $query = SmtpAccount::with('user');

        if ($request->has('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $smtpAccounts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.smtp.index', compact('smtpAccounts'));
    }

    public function show(SmtpAccount $smtpAccount)
    {
        $smtpAccount->load(['user', 'campaigns']);
        
        return view('admin.smtp.show', compact('smtpAccount'));
    }

    public function toggleStatus(SmtpAccount $smtpAccount)
    {
        $smtpAccount->update(['is_active' => !$smtpAccount->is_active]);

        return back()->with('success', 'Status updated');
    }

    public function setVerified(SmtpAccount $smtpAccount)
    {
        $smtpAccount->update(['is_verified' => true]);

        return back()->with('success', 'SMTP verified');
    }

    public function destroy(SmtpAccount $smtpAccount)
    {
        $smtpAccount->delete();

        return redirect()->route('admin.smtp.index')
            ->with('success', 'SMTP account deleted');
    }
}
