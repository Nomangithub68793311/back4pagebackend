<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleText extends Model
{
   use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
   
    protected $fillable=[
     'text','phone','subject'
    ];
    public function sms()
    {
        return $this->belongsTo(Sms::class);
    }
}
