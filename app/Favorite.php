<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['favoritable_type', 'favoritable_id', 'user_id'];

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function favoritable(){
    	return $this->morphTo();
    }


}
