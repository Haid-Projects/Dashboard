<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class BeneficiaryAnswer extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'forms';

    protected $fillable = [
        'beneficiary_form_id',
        'beneficiary_id',
        'illness_id',
        'dimensions',
    ];

    protected $hidden = [
        '_id',

    ];

    public function beneficiaryForm(){
        return $this->belongsTo(BeneficiaryForm::class);
    }
}

