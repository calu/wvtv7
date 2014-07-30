@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{ trans('content.title') }}
@stop

{{-- Content --}}
@section('content')
<div class='row'>
	<div class='col-xs-1'></div>
	<div class='col-xs-3'>
		<div class="panel panel-danger">
			<div class="panel-heading">
				{{HTML::image('img/widgets/bestuur.png') }}
				Bestuur</div>
			<div class="panel-body">
				@include('contents.widget', array('rubriek' => 'bestuur'))
			</div>
		</div>
	</div>
	<div class='col-xs-3 '>midden1</div>
	<div class='col-xs-3 '>rechts1</div>		
</div>

<div class='row'>
	<div class='col-xs-1'></div>	
	<div class='col-xs-3'>
		<div class='panel panel-danger'>
			<div class='panel-heading'>
				{{HTML::image('img/widgets/profiel.png') }}
						Profiel
			</div>
			<div class='panel-body'>
				@if (Sentry::check())
					@include('contents.widget', array('rubriek' => 'profiel'))
				@else
					als je aangemeld bent kan je hier je profiel en wachtwoord wijzigen
				@endif
			</div>
		</div>
	</div>
	<div class='col-xs-3 '>midden2</div>
	<div class='col-xs-3 '>rechts2</div>		
</div>

<div class='row'>
	<div class='col-xs-1'></div>	
	<div class='col-xs-3 '>links2</div>
	<div class='col-xs-3 '>midden2</div>
	<div class='col-xs-3 '>rechts2</div>		
</div>
@stop