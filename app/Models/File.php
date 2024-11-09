<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class File extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'files';

    protected $fillable = [
        'beneficiary_id',
        'diseases',
        'diseases_check_box',
        'general_behaviors',
        'social_skills',
    ];

    protected $casts = [
        'diseases' => 'array',
        'diseases_check_box' => 'array',
        'general_behaviors' => 'array',
        'social_skills' => 'array',
    ];
    protected $hidden=[
        "_id","beneficiary_id"
    ];


}
