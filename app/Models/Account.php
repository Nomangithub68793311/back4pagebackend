<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $hidden = [
        // 'password','hashedPassword'
        'name','email','image'
        
    ];
    protected $fillable=[
    //  'name','email','password','hashedPassword','code'

     'name','email','image'

    ];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
     public function multiple()
    {
        return $this->hasMany(Multiple::class);
    }

}
