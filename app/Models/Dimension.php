<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'illness_id',
        'tips',
        'rank',
        'age_group',
        'max_no'

    ];

    public function illness(){
        return $this->belongsTo(Illness::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }
}
