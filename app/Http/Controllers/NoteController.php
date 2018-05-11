<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
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
    	return $note;
    }

    public function getNotes(Request $request){
    	$user = Auth::user();
    	$note = $request->note;

    }
}
