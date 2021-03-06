<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Cartalyst\Sentry\Users\Eloquent\User implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	/*
	 * Toegevoegd door Johan Calu om een één-op-één relatie aan te
	 * geven met de bestuurstabel
	 * 
	 * bestuur
	 * 
	 */
	public function bestuur(){
		return $this->hasOne('Bestuur');
	}	

	public function userExtra(){
		return $this->belongsTo('UserExtra');
	}
	
	public function getRememberToken()
	{
	    return $this->remember_token;
	}
	
	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}
	
	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

	/**** validatie van user en user_extra ****/
	protected $rules = array(
		'email' => 'required | email',
		'first_name' => 'required | min:2',
		'last_name' => 'required | min:2',
		
	);
	
	protected $errors;
	
	public function myValidate($data)
	{
		$v = Validator::make($data, $this->rules);
		if ($v->fails())
		{
			$this->errors = $v->messages();
			return false;
		}
		return true;
	}
	
	public function errors()
	{
		return $this->errors;
	}
}