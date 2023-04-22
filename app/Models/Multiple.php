<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multiple extends Model
{
    use \App\Traits\TraitUuid;
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable=[
        'country','state','city','service','tag',
        'category','title','description','email',
        'phone','age','images'
    ];

    protected $casts = [
        'city' => 'array',
        'images' => 'array'
    ];
  

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
