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
	$isAdmin = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
	switch($rubriek)
	{
		case 'bestuur' :
			// voornaam, familie, functie -- maar als beheerder ook up/down en bewerk
			// als aangemeld : telefoon,gsm,mail
			$isBestuur = true;
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
		case 'navorming':
			// We maken onderscheid met het Bestuur dat anders is
			$isBestuur = false;	
			$currenttitle = "";
			$headers = null;
			if ($isAdmin){ $headers[] = "";}
			$headers[] = 'omschrijving';
			$headers[] = 'datum';
			$headers[] = 'auteur';
			$headers[] = 'link';
			if ($isAdmin){ $headers[] = "";}
			
			$body = Document::getFulllist($rubriek);
			break;	
		default :
			print("<br />[contents/volledigelijst] de rubriek {$rubriek} werd nog niet geïmplementeerd");
			die("<br />einde");
	}
?>	
{{-- de tabel met de lijst --}}
<div class='titeltekst'>{{ $rubriek }}</div>
@if ($isAdmin)
<?php 
$rubriekpointer = AppHelper::getRubriekpointer($rubriek); 
$urlnieuw = url("{$rubriekpointer}/create"); 
?>

<div><a href="{{ $urlnieuw }}">Een nieuwe item toevoegen</a></div>
@endif

@if ($isBestuur)
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
						@if (is_array($item))
							@if ($item['functie'] == "updown")
							<td>
								<?php  
								// Hier maak je de beide url's aan - en zorg ervoor dat ook de id en de rubriek meegegeven worden 
								$urlup = url('arrow', $parameters = array('id' => $item['id'], 'rubriek' => $rubriek, 'direction' => 'up'));
								$urldown = url('arrow', $parameters = array('id' => $item['id'], 'rubriek' => $rubriek, 'direction' => 'down'));
								?>
								<a href="{{ $urlup }}" rel="tooltip">{{HTML::image('img/up.png') }}</a>
								<a href="{{ $urldown }}" rel="tooltip">{{HTML::image('img/down.png') }}</a>
							</td>
							@endif
							@if ($item['functie'] == "adm")
							<?php
							$urledit = url('edit', $parameters= array('id' => $item['id'], 'rubriek' => $rubriek));
							$urldelete = url('delete', $parameters = array('id'=>$item['id'], 'rubriek' => $rubriek));
							?>
							  <td>
							  	<a href="{{ $urledit }}" rel="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>
							  	<a href="{{ $urldelete }}" rel="tooltip"><span class="glyphicon glyphicon-trash"></span></a> 
							  </td>
							@endif
						@else
							<td>{{$item}}</td>
						@endif
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@else
   <div class='table-responsive'>
   	 <table class='table table-bordered'>
   	 	<thead>
   	 		<tr>
   	 			@foreach( $headers AS $header)
   	 				<th>{{ $header }}</th>
   	 			@endforeach
   	 		</tr>
   	 	</thead>
   	 	<tbody>
   	 		@foreach($body AS $element)
   	 			@if ($currenttitle <> $element['title'])
   	 				<tr><td colspan="<?php echo sizeof($headers) ?>" class='rubriektitel'>{{ $element['title'] }}</td></tr>
   	 				<?php $currenttitle = $element['title']; ?>
   	 			@endif
   	 			<tr>
   	 			@if (isset($element['id']))
	   	 			@if ($element['id']['functie'] == "updown")
							<td class='mycol-120'>
							<?php  
							// Hier maak je de beide url's aan - en zorg ervoor dat ook de id en de rubriek meegegeven worden 
							$urlup = url('arrow', $parameters = array('id' => $element['id']['id'], 'rubriek' => $rubriek, 'direction' => 'up'));
							$urldown = url('arrow', $parameters = array('id' => $element['id']['id'], 'rubriek' => $rubriek, 'direction' => 'down'));
							?>
							<a href="{{ $urlup }}" rel="tooltip">{{HTML::image('img/up.png') }}</a>
							<a href="{{ $urldown }}" rel="tooltip">{{HTML::image('img/down.png') }}</a>
							</td>
					@endif   	 			 
   	 			@endif
   	 			<td>{{ $element['description'] }}</td>
   	 			<td>{{ $element['date'] }}</td>
   	 			<td>{{ $element['author'] }}</td>
   	 			<td><a href="{{ $element['url'] }}" target="_new">link</a></td>
   	 			@if (isset($element['adm']))
 	   	 			@if ($element['adm']['functie'] == "adm")
							<?php
							$urledit = url('edit', $parameters= array('id' => $element['adm']['id'], 'rubriek' => $rubriek));
							$urldelete = url('delete', $parameters = array('id'=>$element['adm']['id'], 'rubriek' => $rubriek));
							?>
							  <td class='mycol-100'>
							  	<a href="{{ $urledit }}" rel="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>
							  	<a href="{{ $urldelete }}" rel="tooltip"><span class="glyphicon glyphicon-trash"></span></a> 
							  </td>
					@endif   	 			
   	 			@endif
   	 			</tr>
   	 			
   	 		@endforeach
   	 	</tbody>
   	 </table>
   </div>
@endif
{{-- knop terugkeren --}}
<div>
	<?php $url = url('inhoud'); ?>
	<a href='{{ $url }}' class='groen'>keer terug</a>
</div>
@stop