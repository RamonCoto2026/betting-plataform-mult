<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CasinoSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'casino_game_id',
        'session_token',
        'status',
        'total_bets',
        'total_winnings',
        'net_result',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'total_bets' => 'decimal:8',
        'total_winnings' => 'decimal:8',
        'net_result' => 'decimal:8',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // Status Constants
    const ACTIVE = 1;
    const CLOSED = 2;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(CasinoGame::class, 'casino_game_id');
    }

    public function bets()
    {
        return $this->hasMany(CasinoBet::class);
    }
}
