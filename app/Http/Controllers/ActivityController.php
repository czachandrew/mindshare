<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Task;
use App\Note;
use \Carbon\Carbon;

class ActivityController extends Controller
{
    public function show(){
    	return view('activity');
    }

    public function create(Request $request){
    	$activity = Activity::create($request->activity);
    	if($request->notes){
    		$note = new Note;
    		$note->content = $request->notes;
    		$note->author_id = Auth::user()->id;
    		$note->noteable_type = 'App\Activity'; 
    		$note->noteable_id = $activity->id; 
    		$note->save();
    	}
    	//check and see if this is the first acitivity for the entity from this user
    	$task = $this->manageFollowup($activity);

    	//if it is then check and see if a follow up is scheduled 

    	//if not then create the follow up activity 

    	$activity->load(['owner','notes','tasks']);
    	return ['activity' => $activity, 'task' => $task];
    }

    public function manageFollowup(Activity $activity){
    	$entity = $activity->activitable;
    	//check and see if there is a task scheduled

    	$followup = $entity->tasks()->where('type', 'Followup')->get();
    	if($followup->count() > 0){
    		//there are tasks associated do nothing 
    		return '';
    	} else {
    		$task = new Task;
    		$task->title = "Follow up with " . $entity->name . " from " . $activity->type . " on " . $activity->created_at;
    		$task->description = ""; 
    		$task->due_date = Carbon::now()->addWeek();
    		$task->status = 'New';
    		$task->type = 'Followup';
    		$task->reminder = true;
    		$task->owner_id = $activity->owner_id;

    		$task->save();

    		$entity->tasks()->save($task);
    		$activity->tasks()->save($task);

    		$task = Task::find($task->id);

    		return $task;

    		//broadcast via echo


    	}

    }
}
