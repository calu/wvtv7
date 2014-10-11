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
		// ook nog $editor
		 $editor = (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary')));
		 if (!$editor){
		 	die("sorry, hier mag je niet komen");
		 }
		?>
		
@section('content')
<h4 class='titeltekst'>toevoegen van een {{ $rubriek }}</h4>

<div class='row'>
	<div class='col-xs-12'>
		terug
	</div>
	<div class='col-xs-12'>
		{{ Form::open(array('action' => 'DocumentsController@store', 'class' => 'form-horizontal')) }}

			{{-- title : is wellicht al aanwezig --}}
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}" for="title">
				{{ Form::label('create_title', trans('documents.title'), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::text('title', '', array('class' => 'form-control', 'placeholder' => $title, 'id' => 'documents_title')) }}
				</div>
				{{ ($errors->has('title') ? 'Geef een titel' : '') }}
			</div>
					
		
		<?php /*
			<div class="form-group {{ ($errors->has('xyz')) ? 'has-error' : '' }}" for="xyz">
				{{ Form::label('create_xyz', trans('documents.xyz'), array('class' => 'col-sm-2 control-label')) }}
				<div class='col-sm-8'>
					{{ Form::text('xyz', '', array('class' => 'form-control', 'placeholder' => trans('documents.xyz'), 'id' => 'documents_xyz')) }}
				</div>
				{{ ($errors->has('xyz') ? 'xyztekst' : '') }}
			</div>
		*/?>	
		
			
		{{ Form::close() }}
		{{-- <div class='rubriektitel'>{{ $title }}</div> --}}
		

	</div>
	<div class='col-xs-12'>
		terug
	</div>	
</div>
@stop