<?php
// we onderstellen hier dat de variabele $rubriek wordt meegegeven.
// Nu halen we een shortlist $lijst op met de data van de desbetreffende rubriek
// 
$lijst = AppHelper::getShortlist($rubriek);

?>

<div>
	<ul class='shorttable'>
		@if ($rubriek == 'profiel')
			<?php
			$user = Sentry::getUser();
			$url = url('changepassword', $parameters = array('id' => $user->id)); 
			$urlprofiel = url('changeprofile', $parameters = array('id' => $user->id));
			?>
			<li><a href = "{{ $url }}">wijzig je wachtwoord</a></li>
			<li><a href = "{{ $urlprofiel }}">wijzig je profiel</a></li>
		@else
			@if (!$lijst)
				<li>Nog geen data</li>
			@else
			   @foreach($lijst AS $item)
			   		<?php $url = url('documentlijst', $parameters = array('rubriek' => $rubriek, 'title' => $item)) ?>
			   		<li><a href= "{{ $url }}"> {{ $item }} </a></li>
			   @endforeach	
			@endif
		@endif
	</ul>
	<div style='clear:both'>
		{{-- link toon de volledige lijst --}}

		<?php $url = url('volledigelijst',$parameters = array('rubriek' => $rubriek)); ?>
		<a href='{{ $url }}' class='groen'>toon de volledige lijst</a>
	</div>
</div>

