<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'specialist_notes',
        'beneficiary_notes',
        'beneficiary_form_id',
        'specialist_id',
        'illness_id',
        'rate',
        'location',
        'name',
        'has_attended'
    ];

    public function specialist(){
        return $this->belongsTo(Specialist::class);
    }
    public function beneficiaryForm(){
        return $this->belongsTo(BeneficiaryForm::class);
    }
    public function beneficiary(){
        return $this->beneficiaryForm->beneficiary;
    }

    public function modificationLogs()
    {
        return $this->hasone(ModificationLog::class);
    }
}
