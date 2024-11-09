<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReReviewRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_form_id',
        'user_id',
        'note',
    ];

    public function beneficiaryForm()
    {
        return $this->belongsTo(BeneficiaryForm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
