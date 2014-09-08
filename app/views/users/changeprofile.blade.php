@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
{{trans('pages.changeprofile')}}
@stop

{{-- Content --}}
@section('content')
<?php
if (isset($id)) $adminbeheer = $id; else $adminbeheer = Sentry::getUser()->id;

$gemachtigd = Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary'));
// haal met de id van deze user de bijhorende userExtra
$extra = DB::table('user_extras')->where('user_id', $adminbeheer)->first();

$selectTitle = AppHelper::enum_to_array('user_extras', 'title');
$selectCountry = DB::table('countries')->lists('name');

?>

<div class="row">
	{{-- knop terugkeren --}}
	<div>
		<?php $url = url('inhoud'); ?>
		<a href='{{ $url }}' class='groen'>keer terug</a>
	</div>

    <div class="col-md-12 roodkader">
        
        {{ Form::open( array('method' => 'post', 'route' => 'storeprofile', 'class' =>'form-inline' )) }}
        
            <h2 class='titeltekst'>{{trans('pages.changeprofile')}}</h2>
            
            
            {{ Form::hidden('adminbeheer', $adminbeheer ) }}

            <div class='row'>
	            <div class="form-group fullwidth" for="id">
	            	
	            	{{ Form::label('edit_id', trans('pages.id'), array('class' => 'col-sm-2 control-label')) }}
	            	{{-- <div class='col-sm-2 '> {{ $adminbeheer }}</div> --}}
		            <div class="col-sm-6">
		              {{ Form::text('id', $adminbeheer , array('class' => 'form-control', 'disabled' => 'disabled', 'placeholder' => trans('pages.id'), 'id' => 'edit_id'))}}
		            </div>	            	
	            </div>
            </div>
            
            <div class="grijstitel">Naam en adres</div>
            {{-- hier komt een test met own.css --}}
           <div class='row'>
           	 <div class='form-group fullwidth'>
           	 	<div class='space-10'>&nbsp;</div>
           	 	{{ Form::label('edit_title', trans('pages.persontitle'), array('class' => 'label-1 control-label')) }}
           	 	<div class='space-20'>&nbsp;</div>
           	 	{{ Form::select('title', $selectTitle,$extra->title, array('class' => 'mycol-100', 'id' => 'edit_title')) }}
           	 	<div class='space-20'>&nbsp;</div>
           	 	{{ Form::label('edit-first_name', trans('pages.first_name'), array('class' => 'label-60 control-label')) }}
           	 	<div class='space-20'>&nbsp;</div>
           	 	{{ Form::text('first_name', $user->first_name, array('class' => 'mycol-200', 'placeholder' => trans('pages.first_name'), 'id' => 'edit_first_name')) }}
           	 	{{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}
           	 	<div class='space-20'>&nbsp;</div>
           	 	{{ Form::label('edit-last_name', trans('pages.last_name'), array('class' => 'label-80 control-label')) }}
           	 	<div class='space-20'>&nbsp;</div>
           	 	{{ Form::text('last_name', $user->last_name, array('class' => 'mycol-200', 'placeholder' => trans('pages.last_name'), 'id' => 'edit_last_name')) }}
           	 </div>
           </div>
           <div class='clearfix'>&nbsp;</div>
           
           <div class='row'>
           		<div class='form-group fullwidth'>
	           	    <div class='space-10'>&nbsp;</div>
	           		{{ Form::label('edit-street', trans('pages.street'), array( 'class' => 'label-1 control-label')) }}   
	           		<div class='space-20'>&nbsp;</div>
	           		{{ Form::text('street', $extra->street, array('class' => 'mycol-400', 'placeholder' => trans('pages.street'), 'id' => 'edit_street'))}}  
	           		{{ ($errors->has('street') ? $errors->first('street') : '') }}
	           		<div class='space-20'>&nbsp;</div>
	           		{{ Form::label('edit_housenr', trans('pages.housenr'), array('class' => 'label-60 control-label')) }} 
	           		<div class='space-20'>&nbsp;</div>
	           		{{ Form::text('housenr', $extra->housenr, array('class' => 'mycol-70', 'placeholder' => trans('pages.housenr'), 'id' => 'edit_housenr'))}}   
	           		{{ ($errors->has('housenr') ? $errors->first('housenr') : '') }}  			
           		</div>

           </div>

           <div class='clearfix'>&nbsp;</div>
           <div class='row'>
           	  {{-- zip city country --}}
           	  <div class='form-group fullwidth'>
           	  	 <div class='space-10'>&nbsp;</div>
           	  	 {{ Form::label('edit_zip', trans('pages.zip'), array( 'class' => 'label-60 control-label')) }}
           	  	 <div class='space-20'>&nbsp;</div>
           	  	 {{ Form::text('zip', $extra->zip, array('class' => 'mycol-70', 'placeholder' => trans('pages.zip'), 'id' => 'edit_zip')) }}
           	  	 {{ ($errors->has('zip') ? $errors->first('zip') : '') }}
           	  	  <div class='space-20'>&nbsp;</div>
           	  	 {{ Form::label('edit_city', trans('pages.city'), array( 'class' => 'label-60 control-label')) }}
           	  	 <div class='space-20'>&nbsp;</div>
           	  	 {{ Form::text('city', $extra->city, array('class' => 'mycol-200', 'placeholder' => trans('pages.city'), 'id' => 'edit_city')) }}
           	  	 {{ ($errors->has('city') ? $errors->first('city') : '') }}
           	  	 <div class='space-20'>&nbsp;</div>
           	  	 {{ Form::label('edit_country', trans('pages.country'), array( 'class' => 'label-60 control-label')) }}
           	  	 <div class='space-20'>&nbsp;</div>
           	  	 {{ Form::select('country', $selectCountry,$extra->country, array('class' => 'mycol-200','placeholder' => trans('pages.country'), 'id' => 'edit_country')) }}
           	  </div>
           </div>
           <div class="grijstitel">persoonlijke data</div>
           
           <div class='row'>
              {{-- e-mail en geboortedatum --}}
              <div class='space-10'>&nbsp;</div>
              {{ Form::label('edit_email', trans('pages.email'), array('class' => 'label-60 control-label')) }}
              <div class='space-20'>&nbsp;</div>
              {{ Form::text('email', $user->email , array('class' => 'mycol-200', 'placeholder' => trans('pages.email'), 'id' => 'edit_email'))}}
              {{ ($errors->has('email') ? $errors->first('email') : '') }}
              <div class='space-20'>&nbsp;</div>
              {{ Form::label('edit_birthdate', trans('pages.birthdate'), array('class' => 'label-90 control-label')) }}
              <div class='space-20'>&nbsp;</div>
              {{ Form::text('birthdate',$extra->birthdate, array('class' => 'mycol-200 datepicker', 'placeholder' => trans('pages.birthdate'),'data-datepicker' => 'datepicker', 'id' => 'edit_birthdate'))}}
              {{ ($errors->has('birthdate') ? $errors->first('birthdate') : '') }}
           </div>
           <div class='clearfix'>&nbsp;</div>
           <div class='row'>
           	 {{-- telefoon en gsm --}}
           	 <div class='space-10'>&nbsp;</div>
           	 {{ Form::label('edit_phone', trans('pages.phone'), array( 'class' => 'label-60 control-label')) }}
           	 <div class='space-20'>&nbsp;</div>
           	 {{ Form::text('phone', $extra->phone, array('class' => 'mycol-200', 'placeholder' => trans('pages.phone'), 'id' => 'edit_phone')) }}
           	 {{ ($errors->has('phone') ? $errors->first('phone') : '') }}
           	 <div class='space-20'>&nbsp;</div>
           	 {{ Form::label('edit_gsm', trans('pages.gsm'), array( 'class' => 'label-1 control-label')) }}
           	 <div class='space-20'>&nbsp;</div>
           	 {{ Form::text('gsm', $extra->gsm, array('class' => 'mycol-200', 'placeholder' => trans('pages.gsm'), 'id' => 'edit_gsm')) }}
           	 {{ ($errors->has('gsm') ? $errors->first('gsm') : '') }}
           </div>
           
           
            <div class='clearfix'>&nbsp;</div>

            <div class='row' style='color:green; width : 100%; text-align: center;'>
            	De passwoorden worden elders gewijzigd (zie home scherm)
            </div>
            <div class="grijstitel">professionele data</div>
            <div class='row'>
            	{{-- diploma functie --}}
            	<div class='space-10'>&nbsp;</div>
            	{{ Form::label('edit_diploma', trans('pages.diploma'), array( 'class' => 'label-60 control-label')) }}
            	<div class='space-20'>&nbsp;</div>
            	{{ Form::text('diploma', $extra->diploma, array('class' => 'mycol-200', 'placeholder' => trans('pages.diploma'), 'id' => 'edit_diploma')) }}
            	{{ ($errors->has('diploma') ? $errors->first('diploma') : '') }}
            	<div class='space-20'>&nbsp;</div>
            	{{ Form::label('edit_diploma', trans('pages.diploma'), array( 'class' => 'label-60 control-label')) }}
            	<div class='space-20'>&nbsp;</div>
            	{{ Form::text('position', $extra->position, array('class' => 'mycol-200', 'placeholder' => trans('pages.position'), 'id' => 'edit_position')) }}
            	{{ ($errors->has('position') ? $errors->first('position') : '') }}            	
            </div>
            <div class='clearfix'>&nbsp;</div>
            {{-- werkplaats --}}
            
            <div class='row'>
            	<div class='space-10'>&nbsp;</div>
            	{{ Form::label('edit_workplace', trans('pages.workplace'), array( 'class' => 'label-60 control-label')) }}
            	<div class='space-20'>&nbsp;</div>
            	{{ Form::text('workplace', $extra->workplace, array('class' => 'mycol-600', 'placeholder' => trans('pages.workplace'), 'id' => 'edit_workplace')) }}
            	{{ ($errors->has('workplace') ? $errors->first('workplace') : '') }}            	
            </div>
            
      {{--      @if (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary'))) --}}
            
            <?php // we halen alle mogelijke groepen op
	            $specsymbol = array('{','}');
	            foreach($userGroups AS $u)
				{
					$temp = str_replace($specsymbol, '', $u);
					$activeGroup[] = $temp;
				} 
				
				// als user activated is .... zet dan checked
				if ($user->activated) $checked = 'checked'; else $checked = '';           	
            ?>
            <div class="grijstitel">enkel voor beheerder</div>
            {{-- de status --}}
            <div class='row'>
	            <div class='space-10'>&nbsp;</div>
	            {{ Form::label('edit-groups', trans('pages.groups'), array('class' => 'label-60 control-label')) }}
	            <div class='space-20'>&nbsp;</div>
            	@foreach($allGroups AS $group) 
            		@if ($gemachtigd)
            		<label >
            			<span onclick="changeProfileGroup(this, {{ $adminbeheer }})" id = "changeProfileGroup"  style="color:white;background-color: {{ (in_array($group->name, $activeGroup) ? 'red' : 'blue')}}">{{ $group->name}}</span>
            		</label>
            		@else
            		<label>
            			<span id = "changeProfileGroup"  style="color:white;background-color: {{ (in_array($group->name, $activeGroup) ? 'red' : 'blue')}}">{{ $group->name}}</span>
            		</label>
            		@endif
            	@endforeach	            	
            </div>
            <div class='clearfix'>&nbsp;</div>	
            <div class='row'>
            	{{-- geactiveerd en laatste login --}}
            	<div class='space-10'>&nbsp;</div>
            	{{ Form::label('edit_activated', trans('pages.activated'), array('class' => 'label-60 control-label') )}}
            	<div class='space-20'>&nbsp;</div>
            	<div class="onoffswitch">
            		<input type="checkbox" name="activatedswitch" class="onoffswitch-checkbox" id="activated" {{ $checked }} />
            		<label class="onoffswitch-label" for="activated">
            			<span class="onoffswitch-inner"></span>
            			<span class="onoffswitch-switch"></span>
            		</label>     		
            	</div>
            	<div class='space-20'>&nbsp;</div>
            	{{ Form::label('edit-lastloggedin', trans('pages.lastloggedin'), array('class' => 'label-120 control-label')) }}
            	<div class='space-20'>&nbsp;</div>
            	{{ Form::text('lastloggedin', $user->last_login, array('class' => 'mycol-200',  'placeholder' => '', 'disabled' => 'disabled', 'id' => 'lastloggedin')) }}
            </div>
            
            <div class='clearfix'>&nbsp;</div>	
            <div class='row'>
            	{{-- in het bestuur --}}
            	<?php
            	if ( Bestuur::isMemberOfBestuur($adminbeheer)) $checked = 'ja'; else $checked = 'neen'; 
				
            	?>
            	<div class='space-10'>&nbsp;</div>
            	{{ Form::label('edit_inbeheer', trans('pages.inbeheer'), array('class' => 'label-120 control-label') )}}
            	<div class='space-20'>&nbsp;</div>      
            	{{ Form::text('inbeheer', $checked, array('class' => 'mycol-100',  'placeholder' => '', 'disabled' => 'disabled', 'id' => 'inbeheer')) }}     	
            </div>            
            		            
            {{-- @endif --}}
			 
            {{ Form::submit('Spaar het profiel', array('class' => 'btn btn-primary')) }}
            
        {{ Form::close() }}
    </div>
</div>
{{-- knop terugkeren --}}
<div>
	<?php $url = url('inhoud'); ?>
	<a href='{{ $url }}' class='groen'>keer terug</a>
</div>
@stop