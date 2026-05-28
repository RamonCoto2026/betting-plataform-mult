<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CryptoPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'crypto_id',
        'price',
        'timestamp',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'timestamp' => 'datetime',
    ];

    // Relationships
    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'crypto_id');
    }
}
