<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CryptoWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crypto_id',
        'amount',
        'wallet_address',
        'transaction_hash',
        'network_fee',
        'status',
        'notes',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'network_fee' => 'decimal:8',
        'status' => 'integer',
        'processed_at' => 'datetime',
    ];

    // Status Constants
    const PENDING = 0;
    const PROCESSING = 1;
    const COMPLETED = 2;
    const FAILED = 3;
    const CANCELLED = 4;

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

    public function scopeCompleted($query)
    {
        return $query->where('status', self::COMPLETED);
    }
}
