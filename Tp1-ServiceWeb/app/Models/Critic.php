<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critic extends Model
{
    use HasFactory;

    protected $fillable = ['score', 'review', 'user_id', 'film_id'];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}