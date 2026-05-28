<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cryptocurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'binance_symbol',
        'image',
        'description',
        'current_price',
        'last_updated',
        'min_bet',
        'max_bet',
        'status',
    ];

    protected $casts = [
        'current_price' => 'decimal:8',
        'min_bet' => 'decimal:8',
        'max_bet' => 'decimal:8',
        'last_updated' => 'datetime',
        'status' => 'boolean',
    ];

    // Relationships
    public function bets()
    {
        return $this->hasMany(Bet::class, 'crypto_id');
    }

    public function deposits()
    {
        return $this->hasMany(CryptoDeposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(CryptoWithdrawal::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(CryptoPriceHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Popular cryptocurrencies
    const BTC = 'BTC';  // Bitcoin
    const ETH = 'ETH';  // Ethereum
    const USDT = 'USDT'; // Tether
    const USDC = 'USDC'; // USD Coin
    const BNB = 'BNB';  // Binance Coin
    const XRP = 'XRP';  // Ripple
    const ADA = 'ADA';  // Cardano
    const SOL = 'SOL';  // Solana
}
