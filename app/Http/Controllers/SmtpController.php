<?php

namespace App\Http\Controllers;

use App\Models\SmtpAccount;
use App\Services\SmtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SmtpController extends Controller
{
    protected SmtpService $smtpService;

    public function __construct(SmtpService $smtpService)
    {
        $this->smtpService = $smtpService;
    }

    /**
     * Display list of SMTP accounts
     */
    public function index(): View
    {
        $accounts = $this->smtpService->getUserSmtpAccounts(Auth::id());

        return view('smtp.index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return view('smtp.create');
    }

    /**
     * Store new SMTP account
     */
    public function store(Request $request): RedirectResponse
    {
        $account = $this->smtpService->createSmtpAccount(Auth::id(), $request->all());

        if (!$account) {
            return back()->withErrors($this->smtpService->getErrors())->withInput();
        }

        return redirect()->route('smtp.index')
            ->with('success', 'SMTP account created successfully!' . 
                ($account->is_verified ? ' Connection verified.' : ' Please test the connection.'));
    }

    /**
     * Show edit form
     */
    public function edit(int $id): View|RedirectResponse
    {
        $account = SmtpAccount::where('user_id', Auth::id())->findOrFail($id);

        return view('smtp.edit', [
            'account' => $account,
        ]);
    }

    /**
     * Update SMTP account
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $account = SmtpAccount::where('user_id', Auth::id())->findOrFail($id);

        if (!$this->smtpService->updateSmtpAccount($account, $request->all())) {
            return back()->withErrors($this->smtpService->getErrors())->withInput();
        }

        return redirect()->route('smtp.index')
            ->with('success', 'SMTP account updated successfully!');
    }

    /**
     * Delete SMTP account
     */
    public function destroy(int $id): RedirectResponse
    {
        $account = SmtpAccount::where('user_id', Auth::id())->findOrFail($id);

        $this->smtpService->deleteSmtpAccount($account);

        return redirect()->route('smtp.index')
            ->with('success', 'SMTP account deleted successfully.');
    }

    /**
     * Set as default
     */
    public function setDefault(int $id): RedirectResponse
    {
        $account = SmtpAccount::where('user_id', Auth::id())->findOrFail($id);
        $account->setAsDefault();

        return back()->with('success', 'Default SMTP account updated.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $account = SmtpAccount::where('user_id', Auth::id())->findOrFail($id);
        $account->update(['is_active' => !$account->is_active]);

        $status = $account->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "SMTP account {$status}.");
    }

    /**
     * Test SMTP connection
     */
    public function test(int $id): RedirectResponse
    {
        $account = SmtpAccount::where('user_id', Auth::id())->findOrFail($id);

        $result = $this->smtpService->retestConnection($account);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Test SMTP without saving (AJAX)
     */
    public function testConfig(Request $request)
    {
        $config = [
            'host' => $request->input('host'),
            'port' => (int) $request->input('port'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'encryption' => $request->input('encryption'),
        ];

        $result = $this->smtpService->testConnection($config);

        return response()->json($result);
    }
}
