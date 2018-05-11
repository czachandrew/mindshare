<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['company_id','main_contact','value','last_activity','status','owner_id'];

    public function company(){
    	return $this->belongsTo('App\Company');
    }

    public function mainContact(){
    	return $this->hasOne('App\Contact','main_contact');
    }

    public function owner(){
    	return $this->belongsTo('App\Owner');
    }

    public function quotes(){
    	return $this->hasMany('App\Quote');
    }
}
