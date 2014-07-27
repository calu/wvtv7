<?php
// we onderstellen hier dat de variabele $rubriek wordt meegegeven.
// Nu halen we een shortlist $lijst op met de data van de desbetreffende rubriek
// 
$lijst = AppHelper::getShortlist($rubriek);

?>

<div>
	<ul class='shorttable'>
		@if (!$lijst)
			<li>Nog geen data</li>
		@else
			<li>TODO #8 geef hier de shortlist </li>
		@endif
	</ul>
	<div style='clear:both'>
		{{-- link toon de volledige lijst --}}
		{{-- TODO #9 @include('contents.volledigelijst')->with('rubriek' => $rubriek) --}}
	</div>
</div>

