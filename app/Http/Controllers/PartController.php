<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Part;
use Storage; 
use Excel;
use Auth;
use Log;
//really we may not need auth except for create

class PartController extends Controller
{
    public function __construct(){

    }

    public function show(){
    	return view('part');
    }

    public function partsAdmin(){
        return view('parts');
    }

    public function create(Request $request){
    	//determine if this use is authrized to create parts
    	$user = Auth::user();
    	if($user){
    		//ok this is a logged in user go ahead and create
    		$part = Part::create($request->all());
    		return $part;
    	} else {
    		//return an error there is not authenticated user associated with this request
    	}
    }

    public function import(Request $request){
        $file = $request->file('file');
        $name = 'PartsUpload';
        $ext = $file->getClientOriginalExtension();
        $new = Storage::putFileAs('/public/uploads', $file, $name .'.'.$ext);
        //load the excel file and create some companies
        $path = 'storage/app/public/uploads/'. $name .'.'.$ext;
        $tarzan = []; 
        $i = 1; 
        Excel::filter('chunk')->load($path)->chunk(250, function($results) use (&$tarzan, &$i)
        {
            foreach($results as $row){
                Log::info($row);
                $part = [
                    'part_number' => $row->partnumber,
                    'description' => $row->shortdescription,
                    'cost' => 0.00,
                    'suggested_price' => $row->retail,
                    'floor' => $row->floor, 
                    'image' => $row->image1, 
                    'status' => 'new',
                    'category' => $row->category,
                    'oem' => $row->oem
                ];

                $newPart = Part::firstOrCreate(['part_number' => $part['part_number']], $part);
                if($newPart->wasRecentlyCreated){
                    //do nothing
                } else {
                    $newPart->update($part);
                }
                array_push($tarzan, $newPart);
            }
        });
        return $tarzan;
    }

    public function update(Part $part, Request $request){
        $part->update($request->all());
        return $part;
    }

    public function list(Request $request){
        Log::info($request->all());
        if($request->filter !== ''){
            $parts = Part::where('part_number', 'like', '%' . $request->filter . '%')->orWhere('description', 'like', '%' . $request->filter . '%')->paginate(25);
        } else {
            $parts = Part::paginate(25);
        }
    	
    	return $parts;
    }

    public function lookup($term){
    	$parts = Part::where('part_number', 'like', '%' . $term . '%')->orWhere('description' ,'like', '%' . $term . '%')->limit(10)->get();

    	return $parts;
    }
}
