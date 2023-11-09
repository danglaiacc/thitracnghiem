<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'text',
        'exam_id',
    ];
    use HasFactory;

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
