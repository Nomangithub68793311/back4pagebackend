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
    // protected $table = 'sms_accounts';

    protected $fillable=[
     'name','phone','hashed_password','password','num_of_sms','white_label_name','free_sms'
    ];

    public function texts()
    {
        return $this->hasMany(SingleText::class);
    }
    public function contacts()
    {
        return $this->hasMany(ImportText::class);
    }
    public function bulk_texts()
    {
        return $this->hasMany(BulkText::class);
    }
     public function packages()
    {
        return $this->hasMany(SoldPackage::class);
    }
}
