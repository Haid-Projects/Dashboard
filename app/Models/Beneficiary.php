<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'user_id',
        'birthdate',
        'relative_relation',
        'gender',
        'marital_status',
        'rate',
        'file_id',
        'socially_integrable',
    ];

    protected $hidden = ['birthdate','file_id']; // Hide the birthdate attribute
    protected $appends = ['age']; // Automatically include age attribute in model's array and JSON representation

    protected $casts = [
        'rate' => 'float', // Cast the rate attribute to float
    ];

    public function forms(){
        return $this->hasMany(BeneficiaryForm::class);
    }

    public function participants(){
        return $this->hasMany(Participant::class);
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->birthdate)->age;
    }

    public function file()
    {
        return File::find($this->file_id);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }


}
