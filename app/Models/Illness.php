<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Illness extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'service_id',
    ];

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function dimesions(){
        return $this->hasMany(Dimension::class);
    }
}
