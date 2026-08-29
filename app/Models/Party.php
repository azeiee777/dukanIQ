<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function udhariEntries()
    {
        return $this->hasMany(UdhariEntry::class);
    }

    /**
     * Net balance for this party.
     * Positive = they owe you (receivable). Negative = you owe them (payable).
     */
    public function balance(): float
    {
        return (float) $this->udhariEntries->sum(fn (UdhariEntry $entry) => $entry->signedAmount());
    }
}
