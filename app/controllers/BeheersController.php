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
		print("edit - BeheersController en id = {$id}");die("xxx");
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
	 	
	 	$date = new DateTime;
		$extraID = DB::table('user_extras')->insertGetID(
			array(
			  'user_id' => 1,
			  'birthdate' => "1951-09-06",
			  'street' => "rozenlaan",
			  'housenr' => "26",
			  'city' => "Oostende",
			  'zip' => "8400",
			  'country' => "Belgium",
			  'phone' => "059 123456",
			  'gsm' => "0476 654321",
			  'workplace' => "thuis",
			  'position' => "the boss",
			  'title' => "dr",
			  'diploma' => "dr.",
			  'created_at' => $date,
			  'updated_at' => $date,
			)
		);

		$extraID = DB::table('user_extras')->insertGetID(
			array(
			  'user_id' => 2,
			  'birthdate' => "1951-09-06",
			  'street' => "rozenlaan",
			  'housenr' => "26",
			  'city' => "Kortrijk",
			  'zip' => "8500",
			  'country' => "Belgium",
			  'phone' => "059 123456",
			  'gsm' => "0476 654321",
			  'workplace' => "thuis",
			  'position' => "the boss",
			  'title' => "dr",
			  'diploma' => "dr.",
			  'created_at' => $date,
			  'updated_at' => $date,
			)
		);
		
		$extraID = DB::table('user_extras')->insertGetID(
			array(
			  'user_id' => 3,
			  'birthdate' => "1951-09-06",
			  'street' => "rozenlaan",
			  'housenr' => "26",
			  'city' => "Brugge",
			  'zip' => "8000",
			  'country' => "Belgium",
			  'phone' => "059 123456",
			  'gsm' => "0476 654321",
			  'workplace' => "thuis",
			  'position' => "the boss",
			  'title' => "dr",
			  'diploma' => "dr.",
			  'created_at' => $date,
			  'updated_at' => $date,
			)
		);		
	 	
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
					$extraID = DB::table('user_extras')->insertGetID(
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
		$documenten = DB::select('SELECT * FROM wvtv_documenten');
		foreach($documenten AS $document)
		{
			$docok = DB::table('documents')->insertGetID(
				array(
					'title' => $document->title,
					'description' => $document->description,
					'url' => $document->url,
					'date' => $document->date,
					'sortnr' => $document->sortnr,
					'localfilename' => $document->localfilename,
					'author' => $document->author,
					'alwaysvisible' => $document->alwaysvisible,
					'type' => 'document'
				)
			);
			
		}
	    print('<br />Alle documenten overgeplaatst');
	 
		$links = DB::select('SELECT * FROM wvtv_links');
		foreach($links AS $link)
		{
			$linkok = DB::table('documents')->insertGetID(
				array(
					'title' => $link->title,
					'description' => $link->description,
					'url' => $link->url,
					'sortnr' => $link->sortnr,
					'localfilename' => $link->localfilename,
					'author' => $link->author,
					'alwaysvisible' => $link->alwaysvisible,
					'type' => 'links'
				)
			);
		}
	    print('<br />Alle links overgeplaatst.');
	 	
	 	$navormingen = DB::select('SELECT * FROM wvtv_navorming');
		foreach($navormingen AS $navorming)
		{
			$linkok = DB::table('documents')->insertGetID(
				array(
					'title' => $navorming->title,
					'description' => $navorming->description,
					'url' => $navorming->url,
					'sortnr' => $navorming->sortnr,
					'localfilename' => $navorming->localfilename,
					'author' => $navorming->author,
					'alwaysvisible' => $navorming->alwaysvisible,
					'type' => 'navorming'
				)
			);
		}
        print('<br />Alle navormingen overgeplaatst');
		
		
		$transfusies = DB::select('SELECT * FROM wvtv_transfusie');
		foreach($transfusies AS $transfusie)
		{
			$transfusieok = DB::table('documents')->insertGetID(
				array(
					'title' => $transfusie->title,
					'description' => $transfusie->description,
					'url' => $transfusie->url,
					'sortnr' => $transfusie->sortnr,
					'localfilename' => $transfusie->localfilename,
					'author' => $transfusie->author,
					'alwaysvisible' => $transfusie->alwaysvisible,
					'type' => 'transfusie'
				)
			);			
		}
		print("<br />Alle transfusies overgeplaatst");
	

		$wetgeving = DB::select('SELECT * FROM wvtv_wetgeving');
		foreach($wetgeving AS $wet)
		{
			$wetgevingok = DB::table('documents')->insertGetID(
				array(
					'title' => $wet->title,
					'description' => $wet->description,
					'url' => $wet->url,
					'sortnr' => $wet->sortnr,
					'localfilename' => $wet->localfilename,
					'author' => null,
					'alwaysvisible' => $wet->alwaysvisible,
					'type' => 'wetgeving'
				)
			);			
		}
		print("<br />Alle wetgeving overgeplaatst");
			 			
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