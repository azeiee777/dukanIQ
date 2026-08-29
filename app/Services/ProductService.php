<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listAll(bool $activeOnly = false)
    {
        return $this->repository->getAllByUser(Auth::id(), $activeOnly);
    }

    public function find(int $id): Product
    {
        return $this->repository->find($id, Auth::id());
    }

    public function createProduct(array $data): Product
    {
        $data['user_id'] = Auth::id();

        return $this->repository->create($data);
    }

    public function updateProduct(int $id, array $data): Product
    {
        return $this->repository->update($id, $data, Auth::id());
    }

    public function deleteProduct(int $id): void
    {
        $this->repository->delete($id, Auth::id());
    }

    /**
     * Record stock coming in (a purchase). Increases quantity and, unless told
     * otherwise, updates the product's cost price to the latest purchase cost.
     */
    public function recordPurchase(int $productId, int $quantity, float $unitCost, string $date, ?string $note = null): StockMovement
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be greater than zero.']);
        }

        return DB::transaction(function () use ($productId, $quantity, $unitCost, $date, $note) {
            $product = $this->find($productId);

            $product->update([
                'quantity' => $product->quantity + $quantity,
                'cost_price' => $unitCost,
            ]);

            return StockMovement::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'type' => StockMovement::TYPE_PURCHASE,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'note' => $note,
                'date' => $date,
            ]);
        });
    }

    /**
     * Record a manual stock adjustment (breakage, correction, personal use).
     * $signedQuantity may be negative (stock out) or positive (stock in).
     */
    public function recordAdjustment(int $productId, int $signedQuantity, string $date, ?string $note = null): StockMovement
    {
        if ($signedQuantity === 0) {
            throw ValidationException::withMessages(['quantity' => 'Adjustment quantity cannot be zero.']);
        }

        return DB::transaction(function () use ($productId, $signedQuantity, $date, $note) {
            $product = $this->find($productId);
            $newQuantity = $product->quantity + $signedQuantity;

            if ($newQuantity < 0) {
                throw ValidationException::withMessages(['quantity' => "Adjustment would take {$product->name} below zero stock."]);
            }

            $product->update(['quantity' => $newQuantity]);

            return StockMovement::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'type' => StockMovement::TYPE_ADJUSTMENT,
                'quantity' => $signedQuantity,
                'unit_cost' => $product->cost_price,
                'note' => $note,
                'date' => $date,
            ]);
        });
    }

    /**
     * Deduct stock for a sale linked to a product. Records the movement at the
     * product's current cost price so historical margin stays accurate even if
     * the cost price changes later.
     */
    public function recordSaleDeduction(int $productId, int $quantity, string $date, int $transactionId): StockMovement
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be greater than zero.']);
        }

        return DB::transaction(function () use ($productId, $quantity, $date, $transactionId) {
            $product = $this->find($productId);

            if ($product->quantity < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Not enough stock for {$product->name} (only {$product->quantity} left).",
                ]);
            }

            $product->update(['quantity' => $product->quantity - $quantity]);

            return StockMovement::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'type' => StockMovement::TYPE_SALE,
                'quantity' => -$quantity,
                'unit_cost' => $product->cost_price,
                'transaction_id' => $transactionId,
                'date' => $date,
            ]);
        });
    }

    /**
     * Reverses a sale's stock deduction (used when a linked sale is edited or
     * deleted) by returning the quantity to stock and removing the movement.
     */
    public function reverseSaleDeduction(int $transactionId): void
    {
        DB::transaction(function () use ($transactionId) {
            $movement = StockMovement::where('transaction_id', $transactionId)
                ->where('type', StockMovement::TYPE_SALE)
                ->first();

            if (!$movement) {
                return;
            }

            $product = $movement->product;
            if ($product) {
                $product->update(['quantity' => $product->quantity + abs($movement->quantity)]);
            }

            $movement->delete();
        });
    }

    public function totalStockValue(): float
    {
        return (float) $this->listAll()->sum(fn (Product $p) => $p->stockValue());
    }

    public function lowStockProducts()
    {
        return $this->listAll(true)->filter(fn (Product $p) => $p->isLowStock())->values();
    }
}
