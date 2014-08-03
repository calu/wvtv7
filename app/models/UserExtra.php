<?php

class UserExtra extends Elegant {
//	protected $fillable = [];

	public function user(){
		return $this->belongsTo('User');
	}
}