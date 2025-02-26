<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'release_year', 'rating', 'length', 'language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function critics()
    {
        return $this->hasMany(Critic::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_film');
    }
}
