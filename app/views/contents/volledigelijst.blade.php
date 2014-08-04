@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('content.fulllist') }}
@stop

{{-- Content --}}
@section('content')
{{-- knop terugkeren --}}
<div>
	<?php $url = url('inhoud'); ?>
	<a href='{{ $url }}' class='groen'>keer terug</a>
</div>	

<?php
	switch($rubriek)
	{
		case 'bestuur' :
			// voornaam, familie, functie -- maar als beheerder ook up/down en bewerk
			// als aangemeld : telefoon,gsm,mail
			
			$headers = null;
			if (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')))
			{
				$headers[] = "";
			}	
			$headers[] = "voornaam";
			$headers[] = "familienaam";
			if (Sentry::check())
			{
				$headers[] = "telefoon";
				$headers[] = "gsm";
				$headers[] = "e-mail";
			}
			$headers[] = "functie";
			if (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')))
			{
				$headers[] = "";
			}	
			
			$body = Bestuur::getFulllist();		
			break;
		default :
			print("<br />[contents/volledigelijst] de rubriek {$rubriek} werd nog niet geïmplementeerd");
			die("<br />einde");
	}
?>	
{{-- de tabel met de lijst --}}
<div class='titeltekst'>{{ $rubriek }}</div>
<div class='table-responsive'>
	<table class='table table_bordered'>
		<thead>
			<tr>
			@foreach( $headers AS $header)
				<th>{{$header}}</th>
			@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach($body AS $element)
			<tr>
				@foreach($element AS $item)
					<td>{{$item}}</td>
				@endforeach
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
{{-- knop terugkeren --}}
<div>
	<?php $url = url('inhoud'); ?>
	<a href='{{ $url }}' class='groen'>keer terug</a>
</div>
@stop