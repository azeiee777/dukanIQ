<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdhariEntry extends Model
{
    use HasFactory;

    public const TYPE_GIVEN = 'given';       // they owe you more
    public const TYPE_RECEIVED = 'received'; // they paid you back
    public const TYPE_TAKEN = 'taken';       // you owe them more
    public const TYPE_PAID = 'paid';         // you paid them back

    protected $fillable = [
        'user_id',
        'party_id',
        'type',
        'amount',
        'note',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * Signed contribution to the party's balance.
     * Positive = increases what they owe you. Negative = decreases it
     * (or increases what you owe them).
     */
    public function signedAmount(): float
    {
        $multiplier = in_array($this->type, [self::TYPE_GIVEN, self::TYPE_PAID], true) ? 1 : -1;

        return $multiplier * (float) $this->amount;
    }

    public function isReceivableSide(): bool
    {
        return in_array($this->type, [self::TYPE_GIVEN, self::TYPE_RECEIVED], true);
    }
}
