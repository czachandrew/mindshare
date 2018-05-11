<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Task;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('subscribed');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show()
    {
        $companies = Company::limit(20)->get();
        $user = Auth::user(); 
        $tasks = Task::where('owner_id', $user->id)->get();
        return view('home', ['companies' => $companies, 'tasks' => $tasks]);
    }
}
