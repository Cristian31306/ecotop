<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['ecosystem_id', 'question_text', 'options', 'correct_option_index', 'image_url'];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function ecosystem()
    {
        return $this->belongsTo(Ecosystem::class);
    }
}
