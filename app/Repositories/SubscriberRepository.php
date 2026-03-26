<?php

namespace App\Repositories;

use App\Models\Subscriber;

class SubscriberRepository extends BaseRepository
{
    public function __construct(Subscriber $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?Subscriber
    {
        return $this->model->where('email', $email)->first();
    }

    public function getByList(int $listId)
    {
        return $this->model->where('subscriber_list_id', $listId)->get();
    }

    public function getActiveByList(int $listId)
    {
        return $this->model->where('subscriber_list_id', $listId)
            ->where('status', 'active')
            ->get();
    }

    public function getUserSubscribers(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
