<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description','owner_id','status','due_date','type','reminder'];

    protected $with = ['notes'];

    public function activities(){
        return $this->morphedByMany('App\Activity', 'taskable');
    }

    public function companies(){
        return $this->morphedByMany('App\Company', 'taskable');
    }

    public function quotes(){
        return $this->morphedByMany('App\Quote', 'taskable');
    }

    public function author(){
    	return $this->belongsTo('App\User', 'owner_id');
    }
    
    public function scopeOpen($query){
    	return $query->where('status', 'New')->orWhere('status', 'open');
    }

    public function scopeCompleted($query){
    	return $query->where('status', 'completed');
    }

    public function notes(){
    	return $this->morphMany('App\Note', 'noteable');
    }

    public function addNote($note){
        $this->notes()->create($note);
    }
}
