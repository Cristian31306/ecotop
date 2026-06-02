<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    protected $fillable = ['user_id', 'ecosystem_id', 'score', 'base_score', 'time_bonus', 'early_bird_bonus'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ecosystem()
    {
        return $this->belongsTo(Ecosystem::class);
    }
}
