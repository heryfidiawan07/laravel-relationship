<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'phone'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
    
}
