<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function getAllByUser(int $userId, bool $activeOnly = false);

    public function find(int $id, int $userId);

    public function create(array $data);

    public function update(int $id, array $data, int $userId);

    public function delete(int $id, int $userId);
}
