<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


// Session Routes
Route::get('login',  array('as' => 'login', 'uses' => 'SessionController@create'));
Route::get('logout', array('as' => 'logout', 'uses' => 'SessionController@destroy'));
Route::resource('sessions', 'SessionController', array('only' => array('create', 'store', 'destroy')));

// User Routes
Route::get('register', 'UserController@create');
Route::get('users/{id}/activate/{code}', 'UserController@activate')->where('id', '[0-9]+');
Route::get('resend', array('as' => 'resendActivationForm', function()
{
	return View::make('users.resend');
}));
Route::post('resend', 'UserController@resend');
Route::get('forgot', array('as' => 'forgotPasswordForm', function()
{
	return View::make('users.forgot');
}));
Route::post('forgot', 'UserController@forgot');
Route::post('users/{id}/change', 'UserController@change');
Route::get('users/{id}/reset/{code}', 'UserController@reset')->where('id', '[0-9]+');
Route::get('users/{id}/suspend', array('as' => 'suspendUserForm', function($id)
{
	return View::make('users.suspend')->with('id', $id);
}));
Route::post('users/{id}/suspend', 'UserController@suspend')->where('id', '[0-9]+');
Route::get('users/{id}/unsuspend', 'UserController@unsuspend')->where('id', '[0-9]+');
Route::get('users/{id}/ban', 'UserController@ban')->where('id', '[0-9]+');
Route::get('users/{id}/unban', 'UserController@unban')->where('id', '[0-9]+');
Route::resource('users', 'UserController');

// Group Routes
Route::resource('groups', 'GroupController');

Route::get('/', array('as' => 'home', function()
{
	return View::make('home');
}));


// App::missing(function($exception)
// {
//     App::abort(404, 'Page not found');
//     //return Response::view('errors.missing', array(), 404);
// });

// Route voor sponsors
Route::get('disclaimer', array('as' => 'disclaimer', function()
{
	return View::make('disclaimer');
}));

// Routes uit de menubalk
Route::get('inhoud', function(){ return View::make('contents/index'); });
Route::get('beheer', function(){ return View::make('beheers/index'); });

// Routes voor het beheer onderdeel
Route::get('beheer/init', 'BeheersController@init');
Route::get('beheer/checkmail', 'BeheersController@checkmail');


// Routes voor Bestuur
Route::resource('bestuurs', 'BestuursController');
Route::get('bestuurs/edit/{id}', array('as' => 'bestuurs.edit', 'uses' => 'BestuursController@edit'));

// Routes voor Documenten
Route::resource('documents', 'DocumentsController');

Route::get('volledigelijst/{rubriek}', function($rubriek){ return View::make('contents/volledigelijst')->with('rubriek', $rubriek);});

Route::get('arrow/{id}/{rubriek}/{direction}', function($id, $rubriek,$direction){
	AppHelper::moveItem($id, $rubriek, $direction);
	return View::make('contents/volledigelijst')->with('rubriek', $rubriek);
//	 print("id = {$id} en rubriek = {$rubriek} en direction = {$direction}");
});

Route::get('edit/{id}/{rubriek}', function($id, $rubriek){
	switch ($rubriek)
	{
		case "bestuur" :
			// id is de id van de user, niet van bestuur --> dus hier zoeken we de user_id
			$bestuur = DB::table('bestuurs')->where('user_id', $id)->get();			
			return Redirect::route('bestuurs.edit', array($bestuur[0]->id));
			break;
		default :
			die("Routes.php - edit - niet ingevuld voor {$rubriek}");
	}

});



Route::get('delete/{id}/{rubriek}', function($id, $rubriek){ die("delete dit id = {$id} en rubriek = {$rubriek}"); });



