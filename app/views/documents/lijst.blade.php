@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('documents.title') }}
@stop

{{-- Content --}}
		{{-- om terug te keren zijn er verschillende mogelijkheden:
			
			1. als er geen titel is, dan keer je terug naar inhoud pagina
			2. als er titel is, dan keer je terug naar de volledige lijst
			--}}
		<?php
		if ($titel)
		{
			$terug = url("volledigelijst", $parameters = array('rubriek' => $rubriek));
		} else {
			$terug = url("inhoud");
		}
		
		// ook nog $editor
		 $editor = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		?>
		
@section('content')
<h4 class='titeltekst'>{{ $rubriek }}</h4>

@if ($editor)
	{{-- een nieuw item toevoegen aan deze rubriek met dezelfde titel --}}
	<?php 
	$urlnieuw = url("documents/create", $parameters = array('rubriek' => $rubriek, 'title' => $titel)); 
	?>
	<div><a href="{{ $urlnieuw }}">Een nieuwe item toevoegen</a></div>
@endif

<div class='row'>
	<div class='col-xs-12'>
		<a href="{{ $terug }}">terug</a>
	</div>
	<div class='col-xs-12'>
		<div class='rubriektitel'>{{ $titel }}</div>
		<table class="table table-striped table-hover">
			<thead>
				@if ($editor)
					<th></th>
				@endif
				<th>omschrijving</th>
				<th>datum</th>
				<th>auteur</th>
				<th>link</th>
				@if ($editor)
					<th></th>
				@endif
			</thead>
			<tbody>
				@foreach($lijst AS $item) 
					<tr>
						@if ($editor)
							<td>up down</td>
						@endif
						<td>{{ $item->description }}</td>
						<td>{{ $item->date }}</td>
						<td>{{ $item->author}} </td>
						<td> link </td>
						@if ($editor)
							<td>edit delete</td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>		

	</div>
	<div class='col-xs-12'>
		<a href="{{ $terug }}">terug</a>
	</div>	
</div>
@stop