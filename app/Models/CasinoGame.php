<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CasinoGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'image',
        'description',
        'provider',
        'rtp',
        'min_bet',
        'max_bet',
        'status',
        'featured',
        'order',
    ];

    protected $casts = [
        'rtp' => 'decimal:2',
        'min_bet' => 'decimal:8',
        'max_bet' => 'decimal:8',
        'status' => 'boolean',
        'featured' => 'boolean',
    ];

    // Game Categories
    const SLOTS = 'slots';
    const TABLE_GAMES = 'table_games';
    const LIVE_CASINO = 'live_casino';
    const CARD_GAMES = 'card_games';
    const VIDEO_POKER = 'video_poker';
    const SPECIALTY = 'specialty';

    // Relationships
    public function sessions()
    {
        return $this->hasMany(CasinoSession::class);
    }

    public function bets()
    {
        return $this->hasMany(CasinoBet::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', 1);
    }
}
