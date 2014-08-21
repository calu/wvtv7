@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('pages.changepassword')}}
@stop

{{-- Content --}}
@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        
            <h2 class='titeltekst'>Je wachtwoord is met succes gewijzigd</h2>
            
    </div>
</div>


@stop