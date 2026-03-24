<?php

namespace App\Interfaces;

interface TransactionRepositoryInterface
{
    /**
     * Get the base query builder for a user (used for chaining filters).
     */
    public function getBaseQuery(int $userId);

    /**
     * Get paginated or limited recent transactions.
     */
    public function getByUser(int $userId, int $limit = 10);

    /**
     * Get all transactions for a user (used for total stats).
     */
    public function getAllByUser(int $userId);

    /**
     * Create a new transaction.
     */
    public function create(array $data);

    /**
     * Update a transaction that belongs to a specific user.
     */
    public function update(int $id, array $data, int $userId);

    /**
     * Delete a transaction belonging to a specific user.
     */
    public function delete(int $id, int $userId);
}
