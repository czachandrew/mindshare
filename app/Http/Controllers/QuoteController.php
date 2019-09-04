<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;
use App\Company;
use App\LineItem;
use Log;
use PDF;
use Storage;
use Mail;

class QuoteController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function show(Quote $quote){
    	//let's decided edit or create 
        $quote->load(['lineitems', 'notes']);

    	return view('quote', ['company' => $quote->company, 'quote' => $quote, 'start' => 'edit']);
    }

    public function test(){
        return ['status' => 'success'];
    }

    public function download(Quote $quote){
    	$quote->load('lineitems', 'shippingAddress','billingAddress', 'company','owner')->find(17);
    	Log::info($quote);
    	//return view('printquote', ['quote' => $quote]);
    	$pdf = PDF::loadView('printquote', ['quote' => $quote]);
    	return $pdf->download('Quote ' . $quote->ref_number .'.pdf');

    }

    public function createLiteQuote(Request $request){
        //contains customer, customerEmail and line items 
        $data = $request->all();
        $data['ref_number'] = "QUOTE#003";
        $pdf = PDF::loadview('litequote', ['quote' => $data]);
        $link =  $pdf->download()->getOriginalContent();
        $doc = Storage::put('public/pdf/Quote for ' . $data['customer'] . '.pdf', $link);
        $path = $path = Storage::disk('public')->path('pdf/Quote for ' . $data['customer'] . '.pdf');
        Mail::raw('Here is your quote', function ($message) use ($path , $data) {
            $message->attach($path);
            $message->subject('Your quote is ready for ' . $data['customer']);
            $message->from('dude@quotedude.com', 'QuoteDude');
            $message->to('gavalex@me.com');
        });

        return ['success' => $doc];
    }

    public function create(Company $company){
        $quote =  new Quote;
    	return view('quote', ['company' => $company, 'quote' => $quote, 'start' => 'create']);
    }

    public function update(Quote $quote, Request $request){
        $quote->update($request->quote);
        foreach($request->lineitems as $lineitem){
            if(array_key_exists('id', $lineitem)){
                $obj = LineItem::find($lineitem['id']);
                $obj->update($lineitem);
            } else {
                //this is a new line item 
                $quote->addLineItem($lineitem);
            }
 
        }
        return $quote->load('lineitems');
    }

    public function delete(Quote $quote){
        $quote->lineitems()->forceDelete();
        return ['success'];
    }

    public function save(){
    	$quoteData = request('quote');
    	$lineitems = request('lineitems');

    	$quote = Quote::create($quoteData);

    	$quote->ref_number = $quote->id + 1000; 
    	$quote->title = $quote->title . "#" . $quote->ref_number;

    	$quote->save();

    	foreach($lineitems as $lineitem){
    		$quote->addLineItem($lineitem);
    	}

    	return $quote->load(['lineitems', 'company', 'shippingAddress', 'billingAddress']);
    }
}
