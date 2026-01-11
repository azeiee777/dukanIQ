<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getBaseQuery(int $userId)
    {
        return Transaction::where('user_id', $userId);
    }

    public function getByUser(int $userId, int $limit = 10)
    {
        return Transaction::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getAllByUser(int $userId)
    {
        return Transaction::where('user_id', $userId)->get();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function delete(int $id, int $userId)
    {
        return Transaction::where('id', $id)
            ->where('user_id', $userId)
            ->delete();
    }
}