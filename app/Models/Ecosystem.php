<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecosystem extends Model
{
    protected $fillable = [
        'day_number',
        'title',
        'content',
        'is_active',
        'available_from',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'available_from' => 'datetime',
            'content' => 'array',
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function scores()
    {
        return $this->hasMany(UserScore::class);
    }
}
