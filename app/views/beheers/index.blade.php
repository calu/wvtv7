@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('beheer.beheer')}}
@stop

{{-- Content --}}
@section('content')

<div class='container-fluid col-md-offset-3 col-md-6 roodkader'>
  <ol>
    @if (Sentry::check() && Sentry::getUser()->hasAccess('admin'))
      <li> <a href="{{ URL::to('beheer/init') }}">{{trans('beheer.init')}}</a></li>
    @endif
		
    @if (Sentry::check() && Sentry::getUser()->hasAccess('admin'))
      <li> <a href="{{ URL::to('beheer/checkmail') }}">{{trans('beheer.checkmail')}}</a></li>
    @endif	
							
  </ol>    
</div>

@stop