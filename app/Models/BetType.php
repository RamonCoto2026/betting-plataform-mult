<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BetType extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_id',
        'name',
        'slug',
        'description',
        'calculation_method',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Bet Types Constants
    const MONEYLINE = 'moneyline';        // Ganador simple
    const SPREAD = 'spread';              // Diferencia de puntos
    const OVER_UNDER = 'over_under';     // Sobre/Bajo total
    const PARLAY = 'parlay';              // Apuesta múltiple
    const CORRECT_SCORE = 'correct_score'; // Resultado exacto
    const PROP_BET = 'prop_bet';          // Apuestas propias
    const LIVE_BET = 'live_bet';          // Apuestas en vivo
}
