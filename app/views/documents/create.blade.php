@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('documents.header') }}
@stop

{{-- Content --}}
		{{-- om terug te keren zijn er verschillende mogelijkheden:
			
			1. als er geen titel is, dan keer je terug naar inhoud pagina
			2. als er titel is, dan keer je terug naar de volledige lijst
			--}}
		<?php
		// ook nog $editor
		 $editor = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		 if (!$editor){
		 	die("sorry, hier mag je niet komen");
		 }
		 
		// bereken het sortnr :
		//   - hou rekening met type document (rubriek)
		//   - hou rekening dat in één type document elk document volgnr uniek is
		//   - hou rekening dat bij een titel alle volgnr elkaar opvolgen.
		$sortnrRij = AppHelper::berekenVolgnr($rubriek, $title);
		$sortnr = $sortnrRij['sortnr'];
		$oudeindex = $sortnrRij['indexoude'];
//		die("de oude sortnr = {$sortnr}");
		?>
		
@section('content')
<h4 class='titeltekst'>toevoegen van een {{ $rubriek }}</h4>

<div class='row'>
	{{-- knop terugkeren --}}
	<div class='col-xs-12'>
		<?php $url = url('inhoud'); ?>
		<a href='{{ $url }}' class='groen'>keer terug</a>
	</div>
	<div class='col-xs-12'>
		{{ Form::open(array('action' => 'DocumentsController@store', 'class' => 'form-horizontal')) }}

			{{-- title : is wellicht al aanwezig --}}
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}" for="title">
				{{ Form::label('create_title', trans('documents.title'), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::text('title', $title, array('class' => 'form-control', 'placeholder' => trans('documents.title'), 'id' => 'documents_title')) }}
				</div>
				{{ ($errors->has('title') ? 'Geef een titel' : '') }}
			</div>
			
			{{-- description --}}
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}" for="description">
				{{ Form::label('create_description', trans('documents.description'), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::textarea('description', '', array('class' => 'form-control', 'placeholder' => trans('documents.description'), 'id' => 'documents_description')) }}
				</div>
				{{ ($errors->has('description') ? 'geef een beschrijving' : '') }}
			</div>					
			
			{{-- datum --}}
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}" for="date">
				{{ Form::label('create_date', trans('documents.date'), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::text('date', '', array('class' => 'datepicker', 'placeholder' => trans('documents.date'), 'id' => 'documents_date')) }}
				</div>
				{{ ($errors->has('date') ? 'Geef een datum' : '') }}
			</div>			
			
			{{-- auteur --}}
			<div class="form-group {{ ($errors->has('author')) ? 'has-error' : '' }}" for="author">
				{{ Form::label('create_author', trans('documents.author'), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::text('author', '', array('class' => 'form-control', 'placeholder' => trans('documents.author'), 'id' => 'documents_author')) }}
				</div>
				{{ ($errors->has('author') ? 'Vul een auteur in' : '') }}
			</div>			
			{{-- url of lokaal bestand --}}
			<div class='roodkader'>
				<div class="form-group {{ ($errors->has('url')) ? 'has-error' : '' }}" for="url">
					{{ Form::label('create_url', trans('documents.url'), array('class' => 'col-sm-2 control-label')) }}
					<div class='col-sm-8'>
						{{ Form::text('url', '', array('class' => 'form-control', 'placeholder' => trans('documents.url'), 'id' => 'documents_url')) }}
					</div>
					{{ ($errors->has('url') ? 'de url is niet valide' : '') }}
				</div>		
				<div>of</div>
				<div class="form-group {{ ($errors->has('localfilename')) ? 'has-error' : '' }}" for="localfilename">
					{{ Form::label('create_localfilename', trans('documents.localfilename'), array('class' => 'col-sm-2 control-label')) }}
					<div class='col-sm-8'>
						{{ Form::file('localfilename', '', array('class' => 'form-control', 'placeholder' => trans('documents.localfilename'), 'id' => 'documents_localfilename')) }}
					</div>
					{{ ($errors->has('localfilename') ? 'Geef een URL of kies een lokaal bestand' : '') }}
				</div>						
			</div>
			{{-- zichtbaar voor iedereen --}}
			<div class="form-group {{ ($errors->has('alwaysvisible')) ? 'has-error' : '' }}" for="alwaysvisible">
				{{ Form::label('create_alwaysvisible', trans('documents.zichtbaar voor iedereen ? '), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::checkbox('alwaysvisible', '', array('class' => 'form-control', 'id' => 'documents_alwaysvisible')) }}
				</div>
				{{ ($errors->has('alwaysvisible') ? 'is het zichtbaar voor iedereen ?' : '') }}
			</div>		
			
			
			{{-- voeg nu ook nog het type toe --}}
			{{ Form::hidden('type', $rubriek) }}	
			{{ Form::hidden('sortnr', $sortnr) }}
			{{ Form::hidden('indexoude', $oudeindex) }}
			{{-- knop maak --}}
			<div class="form-group" for="xyz">				
				<div class='col-sm-8'>
					{{ Form::submit('Aanmaken', array('class' => 'btn btn-primary')) }}
				</div>
			</div>					
		{{ Form::close() }}
		{{-- <div class='rubriektitel'>{{ $title }}</div> --}}
		

	</div>
	{{-- knop terugkeren --}}
	<div class='col-xs-12'>
		<?php $url = url('inhoud'); ?>
		<a href='{{ $url }}' class='groen'>keer terug</a>
	</div>
</div>
@stop