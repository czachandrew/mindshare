<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\User;
use App\Notifications\UserMentioned;
use Auth;
use Log;

class NoteController extends Controller
{
    public function __construct(){

    }

    public function create(Request $request){
    	Log::info($request->all());
    	$note = Note::create($request->all());
        $note = Note::with('author')->find($note->id);
        //check the body for a mention 
        $users = User::all();
        $mentioned = [];
        foreach($users as $user){
            //check and see if the user is mentioned
            if(strpos($note->content, $user->name)){
                //exists add to mentions
                Log::info('I found one!');
                Log::info($user->name);
                Log::info($note); 
                $mentioned[] = $user->name;
                $obj = User::find($user->id);
                $obj->notify(new UserMentioned($note->noteable_type, $note->noteable, $note->author, $user));
            }
            //add to mentioned array 
            Log::info('The following users were mentioned');
            Log::info($mentioned);
            //send notifications
        }

    	return $note;
    }

    public function getNotes(Request $request){
    	$user = Auth::user();
    	$note = $request->note;

    }
}
