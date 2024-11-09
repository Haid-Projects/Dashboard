<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_form_id',
        'modifications',
        'session_id',
        'average_points_percentage'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
