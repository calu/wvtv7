@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('pages.changepassword')}}
@stop

{{-- Content --}}
@section('content')
<?php
if (isset($id)) $adminbeheer = $id; else $adminbeheer = Sentry::getUser()->id;
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        
        {{ Form::open( array('method' => 'post', 'route' => 'storepassword', 'class' =>'form-horizontal' )) }}
        
            <h2 class='titeltekst'>{{trans('pages.changepassword')}}</h2>
            
            
            {{ Form::hidden('adminbeheer', $adminbeheer ) }}
	        <div class="form-group {{ ($errors->has('oudww')) ? 'has-error' : '' }}" for="oudww">
	
	            {{ Form::label('edit_oudww', trans('pages.oudww'), array('class' => 'col-sm-4 control-label')) }}
	            <div class="col-sm-8">
	              {{ Form::text('oudww', '', array('class' => 'form-control', 'placeholder' => trans('pages.oudww'), 'id' => 'edit_oudww'))}}
	            </div>
	            {{ ($errors->has('oudww') ? $errors->first('oudww') : '') }}    			
	    	</div>

	        <div class="form-group {{ ($errors->has('ww1')) ? 'has-error' : '' }}" for="ww1">
	
	            {{ Form::label('edit_ww1', trans('pages.ww1'), array('class' => 'col-sm-4 control-label')) }}
	            <div class="col-sm-8">
	              {{ Form::text('ww1', '', array('class' => 'form-control', 'placeholder' => trans('pages.ww1'), 'id' => 'edit_ww1'))}}
	            </div>
	            {{ ($errors->has('ww1') ? $errors->first('ww1') : '') }}    			
	    	</div>

	        <div class="form-group {{ ($errors->has('ww2')) ? 'has-error' : '' }}" for="ww2">
	
	            {{ Form::label('edit_ww2', trans('pages.ww2'), array('class' => 'col-sm-4 control-label')) }}
	            <div class="col-sm-8">
	              {{ Form::text('ww2', '', array('class' => 'form-control', 'placeholder' => trans('pages.ww2'), 'id' => 'edit_ww2'))}}
	            </div>
	            {{ ($errors->has('ww2') ? $errors->first('ww2') : '') }}    			
	    	</div>	    	
            {{ Form::submit('Wijzig het wachtwoord', array('class' => 'btn btn-primary')) }}
            
        {{ Form::close() }}
    </div>
</div>


@stop