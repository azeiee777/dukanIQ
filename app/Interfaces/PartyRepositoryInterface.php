<?php

namespace App\Interfaces;

interface PartyRepositoryInterface
{
    public function getAllByUser(int $userId);

    public function find(int $id, int $userId);

    public function create(array $data);

    public function update(int $id, array $data, int $userId);

    public function delete(int $id, int $userId);
}
