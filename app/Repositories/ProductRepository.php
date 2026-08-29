<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllByUser(int $userId, bool $activeOnly = false)
    {
        $query = Product::where('user_id', $userId);

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->orderBy('name')->get();
    }

    public function find(int $id, int $userId)
    {
        return Product::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data, int $userId)
    {
        $product = $this->find($id, $userId);
        $product->update($data);

        return $product->refresh();
    }

    public function delete(int $id, int $userId)
    {
        return Product::where('id', $id)->where('user_id', $userId)->delete();
    }
}
