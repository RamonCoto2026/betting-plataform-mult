<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'image',
        'description',
        'status',
        'order'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function leagues()
    {
        return $this->hasMany(League::class, 'sport_id');
    }

    public function matches()
    {
        return $this->hasManyThrough(MatchModel::class, League::class, 'sport_id', 'league_id');
    }

    public function bets()
    {
        return $this->hasManyThrough(Bet::class, MatchModel::class, 'category_id', 'match_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
