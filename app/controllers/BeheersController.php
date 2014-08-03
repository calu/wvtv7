<?php

class BeheersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /beheers
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /beheers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /beheers
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /beheers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /beheers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /beheers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /beheers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	/****
	 * Toegevoegde functies
	 * 
	 * methode 1 : init - initialiseert de databank met user gegevens 
	 */
	 public function init()
	 {
	 	// Deel 1 : lees de gegevens van wvtv_person in, samen met overeenkomstige wvtv_bestuur entry (als aanwezig)
	 	//   en schrijf het uit in users / userExtra en bestuurs
	 	// Hou er rekening mee dat het wachtwoord opnieuw gegenereerd wordt (wvtvtest) 
	 	// en je moet de eerste (calu) niet meer overzetten.  LET WEL : de nummering verandert, 
	 	// omdat we reeds 3 accounts hebben aangemaakt.
	 	
	 	$persons = DB::select("SELECT * FROM wvtv_person");
		$bestuur = DB::select("SELECT * FROM wvtv_bestuur");
		
		$index = 1;
		foreach($persons AS $person)
		{
			if ($person->id != 1){
				
				// Eerst testen we of deze nog niet bestaat ( e-mail adres)
				$personEmail = $person->email;
				$aantal = DB::table('users')->where('email','=',$personEmail)->count();

				if ($aantal > 0)
				{
					print("<br />------------   Dit e-mail adres is {$personEmail} en bestaat reeds");

				} else {
					print("<br />{$personEmail} wordt toegevoegd.");

				
					$thisUser = Sentry::getUserProvider()->create(array(
				        'email'    => $person->email,
				        'password' => 'wvtvcalu',
				        'first_name' => $person->firstname,
				        'last_name' => $person->lastname,
				        'activated' => 1,
				    ));
					
					// Vervolgens ook aanvullen in userExtra
					$date = new DateTime;
					$extraID = DB::table('userExtra')->insertGetID(
						array(
							'user_id' => $thisUser->id,
							'birthdate' => $person->birthdate,
							'street' => $person->address,
							'housenr' => $person->housenr,
							'city' => $person->city,
							'zip' => $person->zip,
							'country' => $person->country,
							'phone' => $person->phone,
							'gsm' => $person->gsm,
							'workplace' => $person->werkplaats,
							'position' => $person->functie,
							'title' => $person->title,
							'diploma' => $person->diploma,
							'created_at' => $date,
							'updated_at' => $date,
						)
					);
					
					// Tenslotte gaan we na of dit ook een "bestuurder" is
					$member = self::isBestuurder($person->id, $bestuur);				
					if ($member != null)
					{
						// toevoegen aan Bestuur
						$bestuurID = DB::table('bestuurs')->insertGetID(
							array(
								'user_id' => $thisUser->id,
								'bestuursfunctie' => $member->bestuursfunctie,
								'sortnr' => $index++,
								'created_at' => $date,
								'updated_at' => $date,							
							)
						);
					}
				}
			}
				
		}
		
		print("<br />TODO - toevoegen van documenten");
	 	print( "<br />[BeheersController/init - TODO ]") ;
	 }
 
     function isBestuurder($id, $bestuur)
	 {
	 	foreach($bestuur AS $member)
		{
			if ($member->user_id == $id)
				return $member;
		}		
	 	return null;
	 }
	 
	 /****
	  * Toegevoegde functies
	  * 
	  * methode 2 : checkmail - we testen het versturen van een e-mail
	  * 
	  */
	  public function checkmail()
	  {
	  	$data = array('firstname' => 'de tester');
		$afzender = 'johan.calu@gmail.com';
		Mail::send('beheers.emails.checkmail', $data, function($message)
		{
			$message->to('johan.calu@gmail.com', 'Johan Calu Tester')->subject('Een testbericht voor Laravel!');
		});
		Session::flash('success', "Een mail werd verstuurd naar {$afzender} als test");
	  	return Redirect::route('home');
	  }	

}