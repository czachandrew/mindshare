<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task; 
use App\User; 
use App\Company; 
use App\Activity;
use \Carbon\Carbon;
use Auth;
use Log; 

class TaskController extends Controller
{
    public function __construct(){

    }

    public function show(Task $task){
        return view('task', ['task' => $task]);
    }

    public function create(Request $request){
    	Log::info('Creating a task');
    	Log::info($request->all());
    	/** $task = Task::create([
    		'title' => $request->title, 
    		'description' => $request->description, 
    		'due_date' => Carbon::parse($request->due_date),
    		'type' => $request->type,
    		'status' => $request->status,
            'reminder' => $request->reminder,
    		'owner_id' => Auth::user()->id
    	]);
    	$task->save();**/
        $task = Task::create($request->all());
        if($request->taskable_id > 0){
            $company = Company::find($request->taskable_id);
            $company->tasks()->save($task);
        }
        
        
    	$id = $task->id;
    	$task = Task::find($id);
    	return ['data' => $task];
    }

    public function update(Task $task, Request $request){
    	$task->update($request->all());
    	return $task;
    }

    public function delete(Task $task){
    	$task->delete(); 
    	return ['success' => 'Deleted'];
    }

    public function details(Task $task) {
    	return $task;
    }

    public function generate(){
    	//generate 10 tasks
    	//get 10 random unassigned contacts and create outreach tasks for them
    }

    public function list(Request $request){
    	$user = Auth::user(); 
    	$completed = $user->completedTasks;
    	$open = $user->openTasks;

    	return ['open' => $open, 'completed' => $completed]; 
    }
}
