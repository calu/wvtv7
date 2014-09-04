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
            
            <div class='row'>
            	<div class="form-group fullwidth">
					<div class='col-sm-3'>
			        	{{ Form::label('edit_title', trans('pages.persontitle'), array('class' => 'col-sm-1 control-label')) }}
			        	<div class='col-sm-2'>
			        		{{ Form::select('title', $selectTitle,$extra->title, array('class' => 'form-control', 'id' => 'edit_title')) }}		        		
			        	</div>		        		
          			</div>
          			<div class='col-sm-5'>
	            		{{ Form::label('edit-first_name', trans('pages.first_name'), array('class' => 'col-sm-3 control-label')) }}
	            		<div class='col-sm-2'>
	            		{{ Form::text('first_name', $user->first_name, array('class' => 'form-control', 'placeholder' => trans('pages.first_name'), 'id' => 'edit_first_name')) }}
	            		</div>
            		</div>
          			<div class='col-sm-4'>
	            		{{ Form::label('edit-last_name', trans('pages.last_name'), array('class' => 'col-sm-3 control-label')) }}
	            		<div class='col-sm-2'>
	            		{{ Form::text('last_name', $user->last_name, array('class' => 'form-control', 'placeholder' => trans('pages.last_name'), 'id' => 'edit_last_name')) }}
	            		</div>
            		</div>            		
            	</div>
            </div>


            
            {{-- hier komt een test met own.css --}}
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
           	  	 {{ Form::text('country', $extra->country, array('class' => 'mycol-200', 'placeholder' => trans('pages.country'), 'id' => 'edit_country')) }}
           	  </div>
           </div>

            
            
            
            <div class='clearfix'>&nbsp;</div>
            <div class='row'>
            	{{-- zip city country --}}
            	{{ Form::label('edit_zip', trans('pages.zip'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-2">
            		{{ Form::text('zip', $extra->zip, array('class' => 'col-sm-1', 'placeholder' => trans('pages.zip'), 'id' => 'edit_zip')) }}
            	</div>
            	{{ ($errors->has('zip') ? $errors->first('zip') : '') }}
            	
             	{{ Form::label('edit_city', trans('pages.city'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-3">
            		{{ Form::text('city', $extra->city, array( 'placeholder' => trans('pages.city'), 'id' => 'edit_city')) }}
            	</div>
            	{{ ($errors->has('city') ? $errors->first('city') : '') }}           	
            	
             	{{ Form::label('edit_country', trans('pages.country'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-3">
            		{{ Form::text('country', $extra->country, array( 'placeholder' => trans('pages.country'), 'id' => 'edit_country')) }}
            	</div>
            	{{ ($errors->has('country') ? $errors->first('country') : '') }} 
           	
            </div>
                                  
            <div class='row'>
		        <div class="form-group fullwidth {{ ($errors->has('email')) ? 'has-error' : '' }}" for="email">

					<div>
			            {{ Form::label('edit_email', trans('pages.email'), array('class' => 'col-sm-1 control-label')) }}
			            <div class="col-sm-6">
			              {{ Form::text('email', $user->email , array('class' => 'form-control', 'placeholder' => trans('pages.email'), 'id' => 'edit_email'))}}
			            </div>
			            {{ ($errors->has('email') ? $errors->first('email') : '') }}   
		            </div> 			
		    	</div>            
            </div>
            
            <div class='row' style='color:green; width : 100%; text-align: center;'>
            	De passwoorden worden elders gewijzigd (zie home scherm)
            </div>
            
            <div class='row'>&nbsp;</div>
            

            <?php 
            	if ($user->activated) $checked = 'checked'; else $checked = ''; 
				if (Sentry::check() && (Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('secretary'))) $disabled = ''; else $disabled = 'disabled';
            ?>
            
            <div class='row'>
            	{{ Form::label('edit_activated', trans('pages.activated'), array('class' => 'col-sm-2 control-label') )}} &nbsp;
            	<div class="col-sm-2">
					<div class="onoffswitch">
					    <input type="checkbox" name="activatedswitch" class="onoffswitch-checkbox" id="activated" {{ $checked }} {{ $disabled }}>
					    <label class="onoffswitch-label" for="activated">
					        <span class="onoffswitch-inner"></span>
					        <span class="onoffswitch-switch"></span>
					    </label>
					</div>
				</div>
				
				{{ Form::label('edit-lastloggedin', trans('pages.lastloggedin'), array('class' => 'col-sm-1 control-label')) }} &nbsp;
				<div class="col-sm-2">
					{{ Form::text('lastloggedin', $user->last_login, array('class' => 'form-control', 'placeholder' => '', 'id' => 'lastloggedin')) }}
				</div>
            </div>
            
            <?php
            // Hier halen we de items uit userGroups en verwijderen de {} en stoppen die in activegroup
            $specsymbol = array('{','}');
            foreach($userGroups AS $u)
			{
				$temp = str_replace($specsymbol, '', $u);
				$activeGroup[] = $temp;
			}
            ?>
            <div class='row'>
            	{{ Form::label('edit-groups', trans('pages.groups'), array('class' => 'col-sm-1 control-label')) }} &nbsp;
            	
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
	 	        <div class="form-group {{ ($errors->has('birthdate')) ? 'has-error' : '' }}" for="birthdate">
		
		            {{ Form::label('edit_birthdate', trans('pages.birthdate'), array('class' => 'col-sm-4 control-label')) }}
		            
					 		            
		            <div class="col-sm-8">
		              {{ Form::text('birthdate',$extra->birthdate, array('class' => 'form-control datepicker', 'placeholder' => trans('pages.birthdate'),'data-datepicker' => 'datepicker', 'id' => 'edit_birthdate'))}}
		            </div>
		            {{ ($errors->has('birthdate') ? $errors->first('birthdate') : '') }}    			
		    	</div>           	
            </div>
            
 
            
            <div class='clearfix'>&nbsp;</div>
            <div class='row'>
            	{{-- telefoon gsm --}}
            	
            	{{ Form::label('edit_phone', trans('pages.phone'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-4">
            		{{ Form::text('phone', $extra->phone, array('placeholder' => trans('pages.phone'), 'id' => 'edit_phone')) }}
            	</div>
            	{{ ($errors->has('phone') ? $errors->first('phone') : '') }}            	
            	
            	{{ Form::label('edit_gsm', trans('pages.gsm'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-4">
            		{{ Form::text('gsm', $extra->gsm, array('placeholder' => trans('pages.gsm'), 'id' => 'edit_gsm')) }}
            	</div>
            	{{ ($errors->has('gsm') ? $errors->first('gsm') : '') }}  
            </div>            
 
             <div class='clearfix'>&nbsp;</div>
            <div class='row'>
             	{{ Form::label('edit_workplace', trans('pages.workplace'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-10">
            		{{ Form::text('workplace', $extra->workplace, array('class'=> 'col-xs-10', 'placeholder' => trans('pages.workplace'), 'id' => 'edit_workplace')) }}
            	</div>
            	{{ ($errors->has('workplace') ? $errors->first('workplace') : '') }}            	
            </div>
 
             <div class='clearfix'>&nbsp;</div>
            <div class='row'>
             	{{ Form::label('edit_function', trans('pages.function'), array( 'class' => 'col-sm-1 control-label')) }}
            	<div class="col-xs-10">
            		{{ Form::text('function', $extra->position, array('class'=> 'col-xs-10', 'placeholder' => trans('pages.function'), 'id' => 'edit_function')) }}
            	</div>
            	{{ ($errors->has('function') ? $errors->first('function') : '') }}            	
            </div>                         

			 
			 
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