<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom_message extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    protected $fillable =[

        'label','message', 'study_id',  'unique_id'
    ];
}
