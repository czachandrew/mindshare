<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::group([
    'middleware' => 'auth:api'
], function () {
    Route::post('/tasks/create', 'TaskController@create');

    Route::get('/lookups/agents/{term}', 'LookupController@users');

    Route::get('/lookups/companies/{term}', 'LookupController@companies');

    Route::get('/tasks', 'TaskController@list');

    Route::get ('/tasks/{task}', 'TaskController@details');

    Route::get('/tasks/delete/{task}', 'TaskController@delete');

    Route::post('/tasks/update/{task}', 'TaskController@update');

    Route::get('/customers', 'CustomerController@list');

    Route::post('/customers/create', 'CustomerController@create');

    Route::get('customers/{customer}', 'CustomerController@details');

    Route::post('/customers/update/{customer}', 'CustomerController@update');

    Route::get('/customers/delete/{customer}', 'CustomerController@delete');

    Route::get('/customers/lookup/{term}','CustomerController@lookup');

    Route::get('/companies/lookup/{term}', 'CompanyController@lookup');

    Route::post('/companies/create', 'CompanyController@create');

    Route::get('/contacts', 'ContactController@list');

    Route::post('/contacts/create', 'ContactController@create');

    Route::post('/companies/{company}/address/new', 'CompanyController@addAddress');

    Route::post('/address/create', 'CompanyController@createAddress');

    Route::get('/address/lookup/{term}', 'CompanyController@addressLookup');

    Route::get('/quotes/{quote}', 'QuoteController@details');

    Route::post('/quotes/create', 'QuoteController@save');

    Route::get('/parts', 'PartController@list');

    Route::post('/parts/create', 'PartController@create');

    Route::get('/parts/lookup/{term}', 'PartController@lookup');

    Route::post('/notes/create', 'NoteController@create');

    Route::post('/activities/create', 'ActivityController@create');
});
