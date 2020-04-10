<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'portal_id' , 'gender', 'agegroup', 'location', 'bio', 'port_id', 'ethnicity', 'sectors' , 'contactnumber', 'profile_status', 'newsletter_subscription'
    ];
    protected $visible = ['user_id', 'portal_id' , 'gender', 'agegroup', 'location', 'bio', 'port_id', 'ethnicity', 'sectors', 'contactnumber', 'profile_status', 'newsletter_subscription'];
}
