<?php

class DocumentsController extends \BaseController {

	/**
	 * Display a listing of documents
	 *
	 * @return Response
	 */
	public function index()
	{
		$documents = Document::all();

		return View::make('documents.index', compact('documents'));
	}

	/**
	 * Show the form for creating a new document
	 *
	 * @return Response
	 */
	public function create($rubriek, $titel)
	{
		return View::make('documents.create', array('rubriek' => $rubriek, 'title' => $titel));
	}

	/**
	 * Store a newly created document in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
//		var_dump(Input::all());
//		die("DocumentsController@store");
		$rubriek = Input::get('type');
		$validator = Validator::make($data = Input::all(), Document::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		//TODO : hernummer alles met nieuw sortnr !!!!
		$indexoude = Input::get('indexoude');
		$sortnr = Input::get('sortnr');
		AppHelper::herberekenSortnr($indexoude,$sortnr, $rubriek);
		Document::create($data);
		
		return View::make('contents/volledigelijst')->with('rubriek', $rubriek);
	}

	/**
	 * Display the specified document.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$document = Document::findOrFail($id);

		return View::make('documents.show', compact('document'));
	}

	/**
	 * Show the form for editing the specified document.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$document = Document::find($id);

		return View::make('documents.edit', compact('document'));
	}

	/**
	 * Update the specified document in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$document = Document::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Document::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$document->update($data);

		return Redirect::route('documents.index');
	}

	/**
	 * Remove the specified document from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Document::destroy($id);

		return Redirect::route('documents.index');
	}
	
	/*
	 * Toon de documenten in deze rubriek.
	 * 
	 * De weergave varieert van rubriek tot rubriek.
	 * En varieert ook voor gewone gebruiker en secretaris of admin
	 * 
	 * Bij Navorming, transfusie, documentatie : orden per titel - en verder (omschrijving, datum, auteur, link)
	 * Bij links, overheidspublicaties : titel, datum, auteur
	 * 
	 */
	public function documentlijst($rubriek, $titel)
	{
		// Haal de volledige lijst op voor deze rubriek - geordend volgens sortnr !!!!!
		if (isset($titel) && sizeof($titel) > 0)
		{
			$lijst = Document::whereRaw('type = ? and title = ?', array($rubriek,$titel))->orderBy('sortnr')->get();
//			$lijst = Document::whereRaw('type = ? and title = ?', array($rubriek,$titel))->orderBy('sortnr')->paginate(15);
			
		} else 
		{
			$lijst = Document::where('type', $rubriek)->orderBy('sortnr')->get();
		}		
		return View::make('documents.lijst')->with('lijst', $lijst)->with('titel', $titel)->with('rubriek', $rubriek);
	}

}
