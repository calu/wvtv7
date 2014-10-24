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
				$idtype = 'user_id';
				break;
			case 'navorming' :
				$tabel = 'documents';
				$idtype = 'id';
				break;
			default:
				print("<br />deze rubriek {$rubriek} is nog niet geïmplementeerd in AppHelper::moveItem");
				die(" ##### tot hier");
		}
		
		$item = DB::table($tabel)->where($idtype, '=', $id)->get();
		var_dump($item); 
		$sortnr = $item[0]->sortnr ;
		if ($direction == 'up')
		{
			if ( $sortnr == 1) return;
			die("<br />sortnr = {$sortnr} [AppHelper@moveItem]");
			// Nu moeten we de vorige vinden 
			$itemhoger = DB::table($tabel)->where('sortnr', '=', ($sortnr-1))->get();
			// en verwissel sortnr met de huidige
			DB::table($tabel)->where($idtype,$id)->update(array('sortnr' => $sortnr-1));
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
			DB::table($tabel)->where($idtype, $id)->update(array('sortnr' => $sortnr+1));
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
	
	/*
	 * berekenVolgnr
	 * 
	 * @purpose : bereken het juiste sortnr voor een nieuwe fiche in de tabel
	 * @return : een array met een sortnr ( of -1 als het niet kan berekend worden) en de index van de vorige
	 * 
	 * @remarks :
	 *     Bij de berekening moet je enkel de sortnrs ophalen voor een welbepaalde rubriek
	 *     waarbij je in gedachte houdt dat de items met zelfde titel een opeenvolgend sortnr hebben en 
	 *     (dus moet je ook gaan hernummeren -- gebeurt na validatie !!!!! in DocumentsController)
	 * 
	 */
	public static function berekenVolgnr($rubriek, $title)
	{
		// Haal alle items van deze rubriek
		$result = DB::select("SELECT * FROM documents WHERE type = '{$rubriek}' ORDER BY sortnr ");
		// Als er geen items zijn, dan is het sortnr = 1
		if ($result == null || sizeof($result) == 0) return 1;  // Er zijn nog geen items
		// Anders
		//   Zoek het hoogste sortnr met deze title (navorming, transfusie, documentatie  van geen belang in links, overheidspublicaties)
		$maxsortnr = -1; $maxindex = -1;
		for ($index = 0; $index < sizeof($result); $index++)
		{
			$item = $result[$index];
			if ($item->title == $title)
			{
				if ($item->sortnr > $maxsortnr)
				{
					$maxsortnr = $item->sortnr;
					$maxindex = $index;
				}
			} 
		}
		//   geef het een waarde hoger
		return array('sortnr' => $maxsortnr + 1, 'indexoude' => $maxindex);
	}
	
	/*
	 * herberekenSortnr
	 * 
	 * @purpose : een nieuw document werd toegevoegd en in berekenVolgnr (zie hierboven ) werd het sortnr berekend
	 *            en nu moeten alle opeenvolgende fiches een nieuw sortnr krijgen
	 * @args : 
	 *   - indexoude is de index van het record voor het nieuwe record dat wordt toegevoegd
	 *   - sortnr is het nummer dat de eerstvolgende moet krijgen
	 *   - rubriek is de rubriek van het document
	 * 
	 * @return : true if success
	 * 
	 * @remarks : het zou hier kunnen mislopen omdat het toevoegen nog niet is gebeurd en we reeds hernummeren. 
	 *            houd dat in het oog
	 */
	public static function herberekenSortnr($indexoude,$sortnr, $rubriek)
	{
		$result = DB::select("SELECT * FROM documents WHERE type = '{$rubriek}' ORDER BY sortnr ");
		if ($result == null || sizeof($result) == 0) return true; // nog geen items, dus niet hernummeren
		
		for ($index = $indexoude+1; $index < sizeof($result); $index++)
		{
			$item = $result[$index];
			$item->sortnr = $sortnr++;
		}

        return true;
	}

}

?>