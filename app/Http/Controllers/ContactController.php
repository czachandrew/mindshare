<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;

class ContactController extends Controller
{
    public function __construct(){

    }

    public function show(){
    	return view('contact');
    }

    public function create(Request $request){
    	$contact = Contact::create($request->all());
    	return $contact;
    }
}
