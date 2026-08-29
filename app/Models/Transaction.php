<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',        // 'sale' or 'expense'
        'amount',
        'category',    // Nullable category
        'description',
        'date',
        'product_id',  // Optional link to a stock item (sales only)
        'quantity',    // Units sold, when linked to a product
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',           // Casts to Carbon instance
        'amount' => 'decimal:2',    // Ensures 2 decimal places
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The stock item this sale was fulfilled from, if any.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovement()
    {
        return $this->hasOne(StockMovement::class);
    }
}