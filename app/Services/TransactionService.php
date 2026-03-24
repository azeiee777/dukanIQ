<?php

namespace App\Services;

use App\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionService
{
    private const SALE_CATEGORY = 'Sales';

    protected $repository;

    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Core logic for filtering transactions based on Dashboard inputs.
     * Used by both the Dashboard view and the Export feature.
     */
    public function getFilteredTransactions($userId, array $filters)
    {
        $query = $this->repository->getBaseQuery($userId);

        // 1. Date Filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('date', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay()
            ]);
        } elseif (isset($filters['date'])) {
            switch ($filters['date']) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    break;
                case 'month':
                    $query->whereMonth('date', Carbon::now()->month)
                        ->whereYear('date', Carbon::now()->year);
                    break;
                // 'all' does nothing, returning everything
            }
        }

        // 2. Type Filter (Income vs Expense)
        if (isset($filters['type']) && $filters['type'] !== 'all') {
            $query->where('type', $filters['type']);
        }

        // 3. Category Filter
        if (isset($filters['category']) && $filters['category'] !== 'all') {
            $query->where('category', $filters['category']);
        }

        return $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
    }

    public function createTransaction(array $data)
    {
        $data['user_id'] = Auth::id();
        $data = $this->normalizeTransactionData($data);

        return $this->repository->create($data);
    }

    public function updateTransaction(int $id, array $data)
    {
        $data = $this->normalizeTransactionData($data);

        return $this->repository->update($id, $data, Auth::id());
    }

    public function deleteTransaction(int $id)
    {
        return $this->repository->delete($id, Auth::id());
    }

    private function normalizeTransactionData(array $data): array
    {
        if ($data['type'] === 'sale') {
            $data['category'] = self::SALE_CATEGORY;
        }

        return $data;
    }
}
