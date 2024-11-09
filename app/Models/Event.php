<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'name',
        'location',
        'notes',
        'type',
        'state_manager_id',
    ];

    public function state_manager(){
        return $this->belongsTo(StateManager::class);
    }
}
