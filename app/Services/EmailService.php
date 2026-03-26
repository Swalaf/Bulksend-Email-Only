<?php

namespace App\Services;

use App\Repositories\SmtpAccountRepository;
use App\Repositories\CampaignRepository;
use App\Models\SmtpAccount;
use Illuminate\Support\Facades\Mail;

class EmailService extends BaseService
{
    protected SmtpAccountRepository $smtpRepository;
    protected CampaignRepository $campaignRepository;

    public function __construct(
        SmtpAccountRepository $smtpRepository,
        CampaignRepository $campaignRepository
    ) {
        $this->smtpRepository = $smtpRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function sendViaSmtp(
        SmtpAccount $smtpAccount,
        string $to,
        string $subject,
        string $body,
        ?string $fromName = null
    ): bool {
        try {
            $config = [
                'driver' => 'smtp',
                'host' => $smtpAccount->host,
                'port' => $smtpAccount->port,
                'username' => $smtpAccount->username,
                'password' => $smtpAccount->password,
                'encryption' => $smtpAccount->encryption,
            ];

            config(['mail.mailers.smtp' => $config]);

            Mail::send([], [], function ($message) use ($to, $subject, $body, $smtpAccount, $fromName) {
                $message->from($smtpAccount->from_address, $fromName ?? $smtpAccount->from_name)
                    ->to($to)
                    ->subject($subject)
                    ->html($body);
            });

            return true;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }

    public function getUserSmtpAccounts(int $userId)
    {
        return $this->smtpRepository->getUserAccounts($userId);
    }

    public function getActiveSmtpAccounts(int $userId)
    {
        return $this->smtpRepository->getActiveAccounts($userId);
    }
}
