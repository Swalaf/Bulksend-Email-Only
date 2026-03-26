<?php

namespace App\Repositories;

use App\Models\SmtpAccount;

class SmtpAccountRepository extends BaseRepository
{
    public function __construct(SmtpAccount $model)
    {
        parent::__construct($model);
    }

    public function getUserAccounts(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getDefaultAccount(int $userId)
    {
        return $this->model->where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }

    public function getActiveAccounts(int $userId)
    {
        return $this->model->where('user_id', $userId)
            ->where('is_active', true)
            ->get();
    }
}
