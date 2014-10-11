<?php

class AppHelper {

    public static function getShortlist($rubriek)
    {
    	switch ($rubriek)
		{
			case 'bestuur' :
				// Haal uit de tabel "bestuurs" de 3 eerste items
				$tabel = DB::table('bestuurs')->orderBy('sortnr', 'asc')->get();
				for ($i = 0; $i < 4; $i++)
				{
					$item = $tabel[$i];
					$user_array = DB::table('users')->where('id', '=', $item->user_id)->get();
					// en maak er de string : voornaam naam ( functie ) 
					if (sizeof($user_array) == 1)
					{
						$user = $user_array[0];
						$line = $user->first_name." ".$user->last_name;
					}
					
					$functie = $item->bestuursfunctie;
					if (strlen($functie) > 0) $line .= " ({$functie})";
					$ret[] = $line;				
				}
				
				break;
			case 'profiel' :
				$ret = null;
				break;
			case 'navorming' :
				$tabel = DB::select("SELECT DISTINCT(title) FROM Documents WHERE type='navorming' ORDER BY sortnr");
				for ($i = 0; $i < 4; $i++)
				{
					$ret[] = $tabel[$i]->title;
				}
				break;
			default :
				print("<br /> deze rubriek {$rubriek} is nog niet geïmplementeerd");
				die(" ##### tot hier");
		}
    	return $ret;
    }
	
	public static function moveItem($id, $rubriek, $direction)
	{
		// Hier schuiven we het item een plaats omhoog of omlaag voor de rubriek
		switch ($rubriek)
		{
			case 'bestuur' :
				$tabel = 'bestuurs';
				break;
			default:
				print("<br />deze rubriek {$rubriek} is nog niet geïmplementeerd in AppHelper::moveItem");
				die(" ##### tot hier");
		}
		
		$item = DB::table($tabel)->where('user_id', '=', $id)->get();
		$sortnr = $item[0]->sortnr ;
		if ($direction == 'up')
		{
			if ( $sortnr == 1) return;
			// Nu moeten we de vorige vinden 
			$itemhoger = DB::table($tabel)->where('sortnr', '=', ($sortnr-1))->get();
			// en verwissel sortnr met de huidige
			DB::table($tabel)->where('user_id',$id)->update(array('sortnr' => $sortnr-1));
			DB::table($tabel)->where('id', $itemhoger[0]->id)->update(array('sortnr' => $sortnr));
		}
		
		if ($direction == 'down')
		{
			// Wat is het hoogste sortnr?
			$max_sortnr = DB::table($tabel)->max('sortnr');
			// Als het sortnr >= max_sortnr --> return
			if ( $sortnr >= $max_sortnr) return;
			
			// Zoek volgend item met sortnr+1
			$itemvolgend = DB::table($tabel)->where('sortnr', ($sortnr+1))->get();
			// verwissel de sortnrs
			DB::table($tabel)->where('user_id', $id)->update(array('sortnr' => $sortnr+1));
			DB::table($tabel)->where('id', $itemvolgend[0]->id)->update(array('sortnr' => $sortnr));

		}

		return;
	}

    public static function getRubriekpointer($rubriek)
	{
		switch ($rubriek)
		{
			case "bestuur" : $ret = 'bestuurs'; break;
			case "navorming" :
				$ret = 'navorming';
				break;
			default : die("AppHelper::getRubriekpointer : {rubriek } nog niet geïmplementeerd");
		}
		return $ret;
	}
	
	public static function enum_to_array($table, $field)
	{
		$result = DB::select("SHOW FIELDS FROM {$table} LIKE '{$field}'");
		$resultvalue = $result[0]->Type;
		preg_match('/enum\((.*)\)$/', $resultvalue, $matches);
		$enum = array();
		foreach( explode(',', $matches[1]) AS $value)
		{
			$v = trim( $value, "'");
			$enum = array_add($enum, $v, $v);
		}
		return $enum;
	}

}

?>