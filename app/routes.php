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
// Route::get('inhoud', function(){ return View::make('contents/index'); });
Route::get('inhoud', array('as' => 'inhoud', function(){ return View::make('contents/index'); }));
Route::get('beheer', function(){ return View::make('beheers/index'); });

// Routes voor het beheer onderdeel
Route::get('beheer/init', 'BeheersController@init');
Route::get('beheer/checkmail', 'BeheersController@checkmail');


// Routes voor Bestuur
Route::resource('bestuurs', 'BestuursController');
Route::get('bestuurs/edit/{id}', array('as' => 'bestuurs.edit', 'uses' => 'BestuursController@edit'));
Route::get('bestuurs/delete/{id}', array('as' => 'bestuurs.delete', 'uses' => 'BestuursController@destroy'));
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



Route::get('delete/{id}/{rubriek}', function($id, $rubriek){
	switch ($rubriek)
	{
		case "bestuur" :
			// id is de id van de user, niet van het bestuur --> dus haal de user_id op 
			$bestuur = DB::table('bestuurs')->where('user_id', $id)->get();
			return Redirect::route('bestuurs.delete', array($bestuur[0]->id));
			break;
		default:
			die("Routes.php - delete - niet ingevuld voor {$rubriek}");
	} 
});

/**** rubriek Profiel *****/
Route::get('changepassword/{id}', array('as' => 'changepassword', 'uses' => 'UserController@changepassword'));
Route::post('storepassword', array('as' => 'storepassword', 'uses' => 'UserController@storepassword'));
Route::get('passwdsuccess', function(){ return View::make('users/passwdsuccess'); });

Route::get('changeprofile/{id}', array('as' => 'changeprofile', 'uses' => 'UserController@changeprofile'));
Route::post('storeprofile', array('as' => 'storeprofile', 'uses' => 'UserController@storeprofile'));

Route::post('changeprofile/changeprofilegroup', function(){
	die("tot hier");
	// haal groupname uit json
	$data = Input::all();
	if (Request::ajax())
	{
		$groupname = $data['groupname'];
		$id = $data['ditid'];
		if ($id == 1) return Response::json("{ 'result' => 'een' }");
		// We hebben nu de groupname en moeten deze nu gebruiken om in Sentry de group te switchen voor deze gebruiker
		
		$user = Sentry::findUserById($id);
		$group = Sentry::findGroupByName($groupname);
		$usergroup = Sentry::findGroupByName("Users");
		
		// Er zijn 2 mogelijkheden : de gekozen groep was al geselecteerd of niet geselecteerd
		if ($user->inGroup($group))
		{  // de user zit al in deze groep -- we zullen deze groep dus verwijderen (als het niet de usergroep is)
			if ( $groupname != 'Users')
			{
				// verwijder de user uit deze group
				$user->removeGroup($group);
				// en voeg hem toe aan de Users- group
				$user->addGroup($usergroup);
			} // anders .. de group is users, doe niets
		} 
		else {
			// nu voeg je deze toe aan de group en verwijder die uit de andere groepen indien nodig
			$user->addGroup($group);
			
			$groups = Sentry::findAllGroups();
			foreach($groups AS $currentGroup)
			{
				if ($currentGroup != $group)
				{
					if ($user->inGroup($currentGroup))
					{
						$user->removeGroup($currentGroup);
					}
				}
			}
		}

	}

	return Response::json("{ 'result' => 'ok'}");
});

/*** document verwerking ****/

Route::get('documentlijst/{rubriek}/{titel}', array('as' => 'documentlijst', 'uses' => 'DocumentsController@documentlijst'));

Route::get('documents/create/{rubriek}/{titel}', array('as' => 'documents', 'uses' => 'DocumentsController@create'));


/*
Route::filter('csrf', function() {
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
    if (Session::token() != $token)
        throw new Illuminate\Session\TokenMismatchException;
});
*/
