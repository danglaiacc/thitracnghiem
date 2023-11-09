<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'text',
        'is_correct',
        'question_id',
    ];
    use HasFactory;
}
