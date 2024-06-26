<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'text',
        'exam_id',
        'explanation',
        'is_multichoice',
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
