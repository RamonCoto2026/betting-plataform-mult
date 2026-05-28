<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CryptoDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crypto_id',
        'amount',
        'transaction_hash',
        'wallet_address',
        'confirmations',
        'required_confirmations',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'status' => 'integer',
    ];

    // Status Constants
    const PENDING = 0;
    const CONFIRMED = 1;
    const FAILED = 3;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'crypto_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::CONFIRMED);
    }
}
