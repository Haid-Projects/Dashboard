<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiaryForm extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'beneficiary_id',
        'specialist_id',
        'state_manager_id',
        'illness_id',
        'is_opened',
        'state_manager_notes',
        'specialist_notes',
        'total_points',
        'form_id',
        'rank',
        'hidden',

    ];
    protected $hidden = [
        'hidden',
    ];

    public function beneficiary(){
        return $this->belongsTo(Beneficiary::class);
    }


    public function sessions(){
        return $this->hasMany(Session::class);
    }


    public function answers(){
        return $this->hasMany(BeneficiaryAnswer::class);
    }

    public function illness()
    {
        return $this->belongsTo(Illness::class);
    }

    public function stateManager()
    {
        return $this->belongsTo(StateManager::class,'state_manager_id',); // Adjust StateManager with your actual model name
    }
    public function specialist()
    {
        return $this->belongsTo(Specialist::class); // Adjust Specialist with your actual model name
    }

    public function reReviewRequests()
    {
        return $this->hasMany(ReReviewRequest::class);
    }

}
