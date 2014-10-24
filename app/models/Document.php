<?php

class Document extends \Eloquent {

	// Add your validation rules here
	public static $rules = array(
		'title' => 'required',
		'description' => 'required',
		
		'url' => 'url',
		'localfilename' => 'required_without:url',
	);
	   

	// Don't forget to fill this array
	protected $fillable = [
		'id','title','description','url','date','sortnr','localfilename','author','alwaysvisible','type','created_at',
		'updated_at'
	];
	
	/*
	 * getFulllist
	 * 
	 * @args :
	 *   - rubriek is de rubriek dat we weergeven

	 */
	public static function getFulllist($rubriek)
	{
		$loggedon = (Sentry::check());
		$admin = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		
		$documenten = Document::where('type', $rubriek)->orderBy('title', 'sortnr')->get();
		foreach($documenten AS $document)
		{
			if ($document->alwaysvisible || $loggedon)
			{
				$temp = null;
				$id = $document->id;
				if ($admin) $temp['id'] = array('id' => $id, "functie" => 'updown');		
				$temp['title'] = $document->title;
				$temp['description'] = $document->description;
				$temp['url'] = $document->url;
				$temp['date'] = $document->date;
				$temp['author'] = $document->author;
				if ($admin) $temp['adm'] = array('id' => $id, "functie" => 'adm');
				$ret[] = $temp;	
				}
		}
		
		return $ret;
	}


}