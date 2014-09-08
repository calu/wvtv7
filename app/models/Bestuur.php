<?php

class Bestuur extends Elegant {

	// Add your validation rules here
/*	public static $rules = [
	   'title' => 'required'
	];
*/
	public static $rules = array(
		'user_id' => 'required',
	);

	// Don't forget to fill this array
	protected $fillable = array('bestuursfunctie');

	public function user(){
		return $this->belongsTo('User');
	}
		
	
	public static function getFulllist()
	{
		$admin = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		$loggedon = (Sentry::check());
		
		$bestuur = Bestuur::all()->sortBy(function($sortnr){ return $sortnr->sortnr; });
		foreach($bestuur AS $item)
		{
			$temp = null;
			$id = $item->user->id;
			if ($admin) $temp[] = array('id' => $id, "functie" => 'updown');
			$temp[] = $item->user->first_name;
			$temp[] = $item->user->last_name;
			if ($loggedon){
				
				$extraArray = UserExtra::where('user_id','=',$id)->get();
				$extra = $extraArray[0];
				$temp[] = $extra->phone;
				$temp[] = $extra->gsm;
				$temp[] = $item->user->email;
			}
			$temp[] = $item->bestuursfunctie;
			if ($admin) $temp[] = array('id' => $id, "functie" => 'adm');
			$ret[] = $temp;
		}
		return $ret;	
	}
	
	public static function getFullname($user_id)
	{
		$user = User::find($user_id);
		$naam = $user->first_name." ".$user->last_name;
		return $naam;
	}
	
	public static function getPotentialusers()
	{
		// We selecteren hier alle users, behalve deze die reeds in bestuur zitten en ook niet webbeheerder (3 eerste)
		// eerst zoeken we alle bestuurders
		$bestuurders = Bestuur::all();
		$bestuur_id_rij = null;
		foreach($bestuurders AS $bestuur)
		{
			$bestuur_id_rij[] = $bestuur->user_id;
		}
		
		// nu halen we alle users op, met uitzondering van de eerste 3 (webbeheerder) en deze in bestuur
		$users = User::all()->sortBy('last_name');
		$ret[''] = 'Kies een lid';
		foreach($users AS $user)
		{
			$id = $user->id;
			if ($id>3 && !in_array($id, $bestuur_id_rij))
			{
				$temp = $user->first_name." ".$user->last_name;
				$ret[$user->id] = $temp;
			}
		}
//		var_dump($ret); die("tot hier");	
		return $ret;
	}
		
		/**
	 * is the member with user_id == id in bestuur?
	 * 
	 * @param int $id (de user_id of the member)
	 * @return true of false
	 */
	 public static function isMemberOfBestuur($id)
	 {
	 	$result = Bestuur::where('user_id', "$id")->count();
		return $result == 1;
	 }	
}