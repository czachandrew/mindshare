<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['company_id','main_contact','last_activity','status', 'owner_id'];

    public function company(){
    	return $this->belongsTo('App\Company');
    }

    public function mainContact(){
    	return $this->belongsTo('App\Contact', 'main_contact');
    }

    public function owner(){
    	return $this->belongsTo('App\User', 'owner_id');
    }

    public function tasks(){
    	return $this->morphMany('App\Task', 'taskable');
    }

    public function notable(){
    	return $this->morphMany('App\Note', 'noteable');
    }
}
