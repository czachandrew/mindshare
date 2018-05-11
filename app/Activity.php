<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['type','status','owner_id','description','lead_id','customer_id','company_id','sale_id','activitable_type', 'activitable_id'];

    protected $with = ['owner','tasks'];

    public function scopeRecent($query){

    }

    public function activitable(){
        return $this->morphTo();
    }

    public function tasks(){
        return $this->morphToMany('App\Task','taskable');
    }

    public function scopeCalls($query){
        return $query->where('type', 'call');
    }

    public function scopeMostRecent($query){
        $query->orderBy('created_at', 'desc');
    }

    public function owner(){
    	return $this->belongsTo('App\User', 'owner_id');
    }

    public function lead(){
    	return $this->belongsTo('App\Lead');
    }

    public function customer(){
    	return $this->belongsTo('App\Customer');
    }

    public function company(){
    	return $this->belongsTo('App\Company');
    }

    public function sale(){
    	return $this->belongsTo('App\Sale');
    }

    public function notes(){
        return $this->morphMany('App\Note', 'noteable');
    }
}
