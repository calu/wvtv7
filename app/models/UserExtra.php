<?php

class UserExtra extends Elegant {
	protected $fillable = ['title', 'street','housenr','zip','city','country','birthdate','phone','gsm','diploma','position','workplace'];

	public function user(){
		return $this->belongsTo('User');
	}
	
	protected $rules = array(
		'title' => 'required',
		'street' => 'required | min : 4',
		'housenr' => 'required',
		'zip' => 'required',
		'city' => 'required | alpha',
		'country' => 'required | countrychosen'
	);
	
	// birthdate is optional
	// phone en gsm zijn optional
	// diploma, position en werkplaats zijn optioneel
}