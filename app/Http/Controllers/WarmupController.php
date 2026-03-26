<?php

namespace App\Http\Controllers;

use App\Models\EmailWarmup;
use App\Models\SmtpAccount;
use App\Services\WarmupService;
use Illuminate\Http\Request;

class WarmupController extends Controller
{
    public function __construct(
        private WarmupService $warmupService
    ) {}

    public function index()
    {
        $smtpAccounts = SmtpAccount::where('user_id', auth()->id())
            ->with('emailWarmup')
            ->get();

        return view('warmup.index', compact('smtpAccounts'));
    }

    public function show(EmailWarmup $warmup)
    {
        $this->authorizeWarmup($warmup);

        $warmup->load(['smtpAccount', 'warmupEmails', 'dailyStats']);

        $stats = [
            'total_sent' => $warmup->warmupEmails()->count(),
            'total_delivered' => $warmup->warmupEmails()->where('status', 'delivered')->count(),
            'total_opened' => $warmup->warmupEmails()->where('status', 'opened')->count(),
            'total_replied' => $warmup->warmupEmails()->where('status', 'replied')->count(),
        ];

        return view('warmup.show', compact('warmup', 'stats'));
    }

    public function start(Request $request)
    {
        $validated = $request->validate([
            'smtp_account_id' => 'required|exists:smtp_accounts,id',
            'target_daily_limit' => 'nullable|integer|min:50|max:1000',
            'total_days' => 'nullable|integer|min:7|max:90',
        ]);

        $smtpAccount = SmtpAccount::where('user_id', auth()->id())
            ->findOrFail($validated['smtp_account_id']);

        // Check if warmup already exists
        if ($smtpAccount->emailWarmup) {
            return back()->with('error', 'Warmup already exists for this SMTP account');
        }

        $settings = [
            'target_daily_limit' => $validated['target_daily_limit'] ?? 500,
            'total_days' => $validated['total_days'] ?? 30,
            'initial_daily_limit' => 10,
        ];

        $warmup = $this->warmupService->startWarmup($smtpAccount, $settings);

        return redirect()->route('warmup.show', $warmup->id)
            ->with('success', 'Warmup started successfully');
    }

    public function pause(EmailWarmup $warmup)
    {
        $this->authorizeWarmup($warmup);

        $this->warmupService->pauseWarmup($warmup);

        return back()->with('success', 'Warmup paused');
    }

    public function resume(EmailWarmup $warmup)
    {
        $this->authorizeWarmup($warmup);

        $this->warmupService->resumeWarmup($warmup);

        return back()->with('success', 'Warmup resumed');
    }

    public function stop(EmailWarmup $warmup)
    {
        $this->authorizeWarmup($warmup);

        $warmup->update(['status' => EmailWarmup::STATUS_INACTIVE]);

        return redirect()->route('warmup.index')
            ->with('success', 'Warmup stopped');
    }

    private function authorizeWarmup(EmailWarmup $warmup): void
    {
        if ($warmup->smtpAccount->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }
    }
}
