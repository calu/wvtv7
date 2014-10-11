<?php

class Document extends \Eloquent {

	// Add your validation rules here
	public static $rules = array(
		'title' => 'required'
	);
	   

	// Don't forget to fill this array
	protected $fillable = [
		'id','title','description','url','date','sortnr','localfilename','author','alwaysvisible','type','created_at',
		'updated_at'
	];


}