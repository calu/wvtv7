<?php

class Bestuur extends Elegant {

	// Add your validation rules here
/*	public static $rules = [
	   'title' => 'required'
	];
*/
	// Don't forget to fill this array
//	protected $fillable = [];

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
			if ($admin) $temp[] = "adm";
			$ret[] = $temp;
		}
		return $ret;	
	}
		
}