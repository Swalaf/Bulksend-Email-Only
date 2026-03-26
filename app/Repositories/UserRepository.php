<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByRole(string $role)
    {
        return $this->model->where('role', $role)->get();
    }

    public function getActiveUsers()
    {
        return $this->model->where('is_active', true)->get();
    }
}
