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
?>

<script type="text/javascript">
    /* If you have more experience in JavaScript, I recommend not binding the change event this way, I didn't bother much about this part, since I guess it isn't part of the question */
 /* REMOVE
    function change_state(obj){
        if (obj.checked){
            //if checkbox is being checked, add a "checked" class
            obj.parentNode.classList.add("checked");
        }
        else{
            //else remove it
            obj.parentNode.classList.remove("checked");
        }
    }

 $.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});  
    */
</script>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        
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
            <div class='row'>
		        <div class="form-group fullwidth {{ ($errors->has('email')) ? 'has-error' : '' }}" for="email">
		
		            {{ Form::label('edit_email', trans('pages.email'), array('class' => 'col-sm-1 control-label')) }}
		            <div class="col-sm-6">
		              {{ Form::text('email', $user->email , array('class' => 'form-control', 'placeholder' => trans('pages.email'), 'id' => 'edit_email'))}}
		            </div>
		            {{ ($errors->has('email') ? $errors->first('email') : '') }}    			
		    	</div>            
            </div>
            
            <div class='row' style='color:green; width : 100%; text-align: center;'>
            	De passwoorden worden elders gewijzigd (zie home scherm)
            </div>
            
            <div class='row'>&nbsp;</div>
            
            <div class='row'>
	 	        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}" for="first_name">
		
		            {{ Form::label('edit_first_name', trans('pages.first_name'), array('class' => 'col-sm-3 control-label')) }} &nbsp;
		            <div class="col-sm-8">
		              {{ Form::text('first_name', $user->first_name, array('class' => 'form-control', 'placeholder' => trans('pages.first_name'), 'id' => 'edit_first_name'))}}
		            </div>
		            {{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}    			
		    	</div>    
		    	
	 	        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}" for="last_name">
		
		            {{ Form::label('edit_last_name', trans('pages.last_name'), array('class' => 'col-sm-4 control-label')) }} &nbsp;
		            <div class="col-sm-8">
		              {{ Form::text('last_name', $user->last_name, array('class' => 'form-control', 'placeholder' => trans('pages.last_name'), 'id' => 'edit_last_name'))}}
		            </div>
		            {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}    			
		    	</div> 		    	       	
            </div>
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
            	
            		<label class="input-check">
            			<span onclick="changeProfileGroup(this, {{ $adminbeheer }})" id = "changeProfileGroup"  style="background-color: {{ (in_array($group->name, $activeGroup) ? 'red' : 'blue')}}">{{ $group->name}}</span>
            		</label>
            		
            		
            		
            		
<?php /* REMOVE
				<label class="input-check"><input onchange="change_state(this)" type="checkbox" value="{{ (in_array($group->name, $activeGroup) ? 1 : 0)}}" name="{{ $group->name}}" {{ (in_array($group->name, $activeGroup) ? "checked = 'checked'" : "")}} /> {{ $group->name}}</label>
 */ ?>
            	@endforeach
            	
            	
            	&nbsp;en&nbsp;
            	

				
            </div>
            <?php /*           

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
			 * 
			 */ ?>
			 
			 
            {{ Form::submit('Spaar het profiel', array('class' => 'btn btn-primary')) }}
            
        {{ Form::close() }}
    </div>
</div>
<?php /*
            	@foreach($userGroups AS $ugroup)
            	{
            		
            		<script> setProfileGroup("{{ $ugroup }}")</script>
            		{{ $ugroup}}
            	}
            	@endforeach
 */ ?>
 */
@stop