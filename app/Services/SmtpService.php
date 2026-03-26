<?php

namespace App\Services;

use App\Models\SmtpAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Swift_SmtpTransport;
use Swift_Mailer;

class SmtpService extends BaseService
{
    /**
     * Validate SMTP configuration
     */
    public function validateSmtpConfig(array $data): bool
    {
        $rules = [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-_]+$/',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:500',
            'encryption' => 'required|in:tls,ssl',
            'from_address' => 'required|email:rfc,dns',
            'from_name' => 'nullable|string|max:255',
            'daily_limit' => 'nullable|integer|min:1|max:100000',
            'monthly_limit' => 'nullable|integer|min:1|max:1000000',
        ];

        if (!$this->validate($data, $rules)) {
            return false;
        }

        // Validate port based on encryption
        if ($data['encryption'] === 'ssl' && !in_array($data['port'], [465, 994])) {
            $this->errors[] = 'SSL encryption typically uses port 465 or 994';
        }

        if ($data['encryption'] === 'tls' && !in_array($data['port'], [587, 25])) {
            $this->errors[] = 'TLS encryption typically uses port 587 or 25';
        }

        return empty($this->errors);
    }

    /**
     * Test SMTP connection
     */
    public function testConnection(array $config): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'error' => null,
        ];

        try {
            // Create transport
            $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
                ->setEncryption($config['encryption'])
                ->setUsername($config['username'])
                ->setPassword($config['password'])
                ->setTimeout(10);

            // Create mailer
            $mailer = new Swift_Mailer($transport);

            // Try to connect
            $mailer->getTransport()->start();

            $result['success'] = true;
            $result['message'] = 'Connection successful! SMTP credentials are valid.';

        } catch (\Swift_TransportException $e) {
            $result['error'] = $e->getMessage();
            $result['message'] = $this->parseSmtpError($e->getMessage());
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            $result['message'] = 'Connection failed: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Test connection using SmtpAccount model
     */
    public function testSmtpAccount(SmtpAccount $account): array
    {
        return $this->testConnection([
            'host' => $account->host,
            'port' => $account->port,
            'username' => $account->getDecryptedUsername(),
            'password' => $account->getDecryptedPassword(),
            'encryption' => $account->encryption,
        ]);
    }

    /**
     * Parse common SMTP errors
     */
    protected function parseSmtpError(string $error): string
    {
        $error = strtolower($error);

        if (str_contains($error, 'connection refused')) {
            return 'Connection refused. Please check the host and port.';
        }

        if (str_contains($error, 'connection timed out')) {
            return 'Connection timed out. Please check your network and try again.';
        }

        if (str_contains($error, 'authentication failed')) {
            return 'Authentication failed. Please check your username and password.';
        }

        if (str_contains($error, 'could not verify certificate')) {
            return 'SSL certificate verification failed. Try using TLS instead of SSL.';
        }

        if (str_contains($error, 'ssl')) {
            return 'SSL/TLS error. Please check your encryption settings.';
        }

        if (str_contains($error, '550')) {
            return 'Sender address rejected. Please check your from address.';
        }

        if (str_contains($error, '521')) {
            return 'Server does not accept TLS. Please use SSL on port 465.';
        }

        return 'Connection failed. Please check your SMTP settings.';
    }

    /**
     * Create or update SMTP account
     */
    public function createSmtpAccount(int $userId, array $data): ?SmtpAccount
    {
        if (!$this->validateSmtpConfig($data)) {
            return null;
        }

        // Check if name already exists for this user
        $existing = SmtpAccount::where('user_id', $userId)
            ->where('name', $data['name'])
            ->first();

        if ($existing) {
            $this->errors[] = 'An SMTP account with this name already exists.';
            return null;
        }

        // Test connection before saving
        $testResult = $this->testConnection([
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'encryption' => $data['encryption'],
        ]);

        $isVerified = $testResult['success'];

        $account = SmtpAccount::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'encryption' => $data['encryption'],
            'from_address' => $data['from_address'],
            'from_name' => $data['from_name'] ?? null,
            'is_default' => $data['is_default'] ?? false,
            'is_active' => true,
            'daily_limit' => $data['daily_limit'] ?? 500,
            'monthly_limit' => $data['monthly_limit'] ?? 10000,
            'is_verified' => $isVerified,
            'status' => $isVerified ? 'verified' : 'pending',
            'last_tested_at' => now(),
            'last_test_error' => $testResult['error'] ?? null,
        ]);

        // Set as default if requested
        if ($data['is_default'] ?? false) {
            $account->setAsDefault();
        }

        return $account;
    }

    /**
     * Update SMTP account
     */
    public function updateSmtpAccount(SmtpAccount $account, array $data): bool
    {
        $rules = [
            'name' => 'sometimes|string|max:255|regex:/^[a-zA-Z0-9\s\-_]+$/',
            'host' => 'sometimes|string|max:255',
            'port' => 'sometimes|integer|min:1|max:65535',
            'username' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|max:500',
            'encryption' => 'sometimes|in:tls,ssl',
            'from_address' => 'sometimes|email:rfc,dns',
            'from_name' => 'nullable|string|max:255',
            'is_default' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'daily_limit' => 'sometimes|integer|min:1|max:100000',
            'monthly_limit' => 'sometimes|integer|min:1|max:1000000',
        ];

        if (!$this->validate($data, $rules)) {
            return false;
        }

        // If credentials changed, test them
        $credentialsChanged = isset($data['host']) || isset($data['username']) || isset($data['password']);
        
        if ($credentialsChanged) {
            $config = [
                'host' => $data['host'] ?? $account->host,
                'port' => $data['port'] ?? $account->port,
                'username' => $data['username'] ?? $account->getDecryptedUsername(),
                'password' => $data['password'] ?? $account->getDecryptedPassword(),
                'encryption' => $data['encryption'] ?? $account->encryption,
            ];

            $testResult = $this->testConnection($config);
            
            $data['is_verified'] = $testResult['success'];
            $data['status'] = $testResult['success'] ? 'verified' : 'failed';
            $data['last_tested_at'] = now();
            $data['last_test_error'] = $testResult['error'] ?? null;
        }

        $account->update($data);

        // Set as default if requested
        if ($data['is_default'] ?? false) {
            $account->setAsDefault();
        }

        return true;
    }

    /**
     * Re-test SMTP connection
     */
    public function retestConnection(SmtpAccount $account): array
    {
        $result = $this->testSmtpAccount($account);

        if ($result['success']) {
            $account->markAsVerified();
        } else {
            $account->markAsFailed($result['error'] ?? 'Unknown error');
        }

        return $result;
    }

    /**
     * Delete SMTP account
     */
    public function deleteSmtpAccount(SmtpAccount $account): bool
    {
        // If this was the default, we need to handle that
        $wasDefault = $account->is_default;

        $account->delete();

        // If it was default, set another account as default
        if ($wasDefault) {
            $newDefault = SmtpAccount::where('user_id', $account->user_id)
                ->first();
            
            if ($newDefault) {
                $newDefault->setAsDefault();
            }
        }

        return true;
    }

    /**
     * Get user's SMTP accounts
     */
    public function getUserSmtpAccounts(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return SmtpAccount::where('user_id', $userId)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get default SMTP account for user
     */
    public function getDefaultAccount(int $userId): ?SmtpAccount
    {
        return SmtpAccount::where('user_id', $userId)
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get available SMTP for sending
     */
    public function getAvailableForSending(int $userId): ?SmtpAccount
    {
        return SmtpAccount::where('user_id', $userId)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->where(function ($query) {
                $query->whereColumn('emails_sent_today', '<', 'daily_limit')
                    ->orWhereColumn('emails_sent_month', '<', 'monthly_limit');
            })
            ->orderBy('is_default', 'desc')
            ->first();
    }
}
