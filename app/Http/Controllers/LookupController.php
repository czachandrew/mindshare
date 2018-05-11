<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;

class LookupController extends Controller
{
    public function users($term){

    	//$search = request('name');
    	$users = User::where('name', 'LIKE', "$term%")->take(5)->get();

    	return $users;
    }

    public function companies($term){
    	$companies = Company::where('name', 'LIKE', "$term%")->take(5)->get();

    	return $companies;
    }
}