<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'mobile', 'company_id', 'title'];

    public function company(){
    	return $this->belongsTo('App\Company');
    }
}
