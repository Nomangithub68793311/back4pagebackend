<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        'password','hashed_password'
        // 'name','email','image'
        
    ];
    protected $fillable=[
     'name','phone','hashed_password','password'

    //  'name','email','image'

    ];
}
