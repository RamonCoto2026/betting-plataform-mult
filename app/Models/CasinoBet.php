<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CasinoBet extends Model
{
    use HasFactory;

    protected $fillable = [
        'casino_session_id',
        'casino_game_id',
        'user_id',
        'bet_amount',
        'win_amount',
        'result_data',
        'status',
    ];

    protected $casts = [
        'bet_amount' => 'decimal:8',
        'win_amount' => 'decimal:8',
        'result_data' => 'json',
    ];

    // Status Constants
    const PENDING = 0;
    const WON = 1;
    const LOST = 2;
    const DRAW = 3;

    // Relationships
    public function session()
    {
        return $this->belongsTo(CasinoSession::class, 'casino_session_id');
    }

    public function game()
    {
        return $this->belongsTo(CasinoGame::class, 'casino_game_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
