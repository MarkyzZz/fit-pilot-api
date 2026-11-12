<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'force',
    'level',
    'mechanic',
    'equipment',
    'category',
    'primary_muscles',
    'secondary_muscles',
    'instructions',
])]
class Exercise extends Model
{
    protected function casts(): array
    {
        return [
            'primary_muscles' => 'array',
            'secondary_muscles' => 'array',
            'instructions' => 'array',
        ];
    }
}
