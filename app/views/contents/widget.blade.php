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
		   @foreach($lijst AS $item)
		   		<li> {{ $item }} </li>
		   @endforeach	
		@endif
	</ul>
	<div style='clear:both'>
		{{-- link toon de volledige lijst --}}

		<?php $url = url('volledigelijst',$parameters = array('rubriek' => $rubriek)); ?>
		<a href='{{ $url }}' class='groen'>toon de volledige lijst</a>
	</div>
</div>

