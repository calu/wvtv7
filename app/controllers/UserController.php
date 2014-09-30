<?php

use Authority\Repo\User\UserInterface;
use Authority\Repo\Group\GroupInterface;
use Authority\Service\Form\Register\RegisterForm;
use Authority\Service\Form\User\UserForm;
use Authority\Service\Form\ResendActivation\ResendActivationForm;
use Authority\Service\Form\ForgotPassword\ForgotPasswordForm;
use Authority\Service\Form\ChangePassword\ChangePasswordForm;
use Authority\Service\Form\SuspendUser\SuspendUserForm;

class UserController extends BaseController {

	protected $user;
	protected $group;
	protected $registerForm;
	protected $userForm;
	protected $resendActivationForm;
	protected $forgotPasswordForm;
	protected $changePasswordForm;
	protected $suspendUserForm;

	/**
	 * Instantiate a new UserController
	 */
	public function __construct(
		UserInterface $user, 
		GroupInterface $group, 
		RegisterForm $registerForm, 
		UserForm $userForm,
		ResendActivationForm $resendActivationForm,
		ForgotPasswordForm $forgotPasswordForm,
		ChangePasswordForm $changePasswordForm,
		SuspendUserForm $suspendUserForm)
	{
		$this->user = $user;
		$this->group = $group;
		$this->registerForm = $registerForm;
		$this->userForm = $userForm;
		$this->resendActivationForm = $resendActivationForm;
		$this->forgotPasswordForm = $forgotPasswordForm;
		$this->changePasswordForm = $changePasswordForm;
		$this->suspendUserForm = $suspendUserForm;

		//Check CSRF token on POST
		$this->beforeFilter('csrf', array('on' => 'post'));

		// Set up Auth Filters
		$this->beforeFilter('auth', array('only' => array('change')));
		$this->beforeFilter('inGroup:Admins', array('only' => array('show', 'index', 'destroy', 'suspend', 'unsuspend', 'ban', 'unban', 'edit', 'update')));
		//array('except' => array('create', 'store', 'activate', 'resend', 'forgot', 'reset')));
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $users = $this->user->all();
      
        return View::make('users.index')->with('users', $users);
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('users.create');
	}

	/**
	 * Store a newly created user.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Form Processing
        $result = $this->registerForm->save( Input::all() );

        if( $result['success'] )
        {
            Event::fire('user.signup', array(
            	'email' => $result['mailData']['email'], 
            	'userId' => $result['mailData']['userId'], 
                'activationCode' => $result['mailData']['activationCode']
            ));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@create')
                ->withInput()
                ->withErrors( $this->registerForm->errors() );
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $user = $this->user->byId($id);

        if($user == null || !is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        return View::make('users.show')->with('user', $user);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = $this->user->byId($id);

        if($user == null || !is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        $currentGroups = $user->getGroups()->toArray();
        $userGroups = array();
        foreach ($currentGroups as $group) {
        	array_push($userGroups, $group['name']);
        }
        $allGroups = $this->group->all();

        return View::make('users.edit')->with('user', $user)->with('userGroups', $userGroups)->with('allGroups', $allGroups);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		// Form Processing
        $result = $this->userForm->update( Input::all() );

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::action('UserController@show', array($id));

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@edit', array($id))
                ->withInput()
                ->withErrors( $this->userForm->errors() );
        }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		if ($this->user->destroy($id))
		{
			Session::flash('success', 'User Deleted');
            return Redirect::to('/users');
        }
        else 
        {
        	Session::flash('error', 'Unable to Delete User');
            return Redirect::to('/users');
        }
	}

	/**
	 * Activate a new user
	 * @param  int $id   
	 * @param  string $code 
	 * @return Response
	 */
	public function activate($id, $code)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->activate($id, $code);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::route('home');
        }
	}

	/**
	 * Process resend activation request
	 * @return Response
	 */
	public function resend()
	{
		// Form Processing
        $result = $this->resendActivationForm->resend( Input::all() );

        if( $result['success'] )
        {
            Event::fire('user.resend', array(
				'email' => $result['mailData']['email'], 
				'userId' => $result['mailData']['userId'], 
				'activationCode' => $result['mailData']['activationCode']
			));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');
        } 
        else 
        {
            Session::flash('error', $result['message']);
            return Redirect::route('profile')
                ->withInput()
                ->withErrors( $this->resendActivationForm->errors() );
        }
	}

	/**
	 * Process Forgot Password request
	 * @return Response
	 */
	public function forgot()
	{
		// Form Processing
        $result = $this->forgotPasswordForm->forgot( Input::all() );

        if( $result['success'] )
        {
            Event::fire('user.forgot', array(
				'email' => $result['mailData']['email'],
				'userId' => $result['mailData']['userId'],
				'resetCode' => $result['mailData']['resetCode']
			));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');
        } 
        else 
        {
            Session::flash('error', $result['message']);
            return Redirect::route('forgotPasswordForm')
                ->withInput()
                ->withErrors( $this->forgotPasswordForm->errors() );
        }
	}

	/**
	 * Process a password reset request link
	 * @param  [type] $id   [description]
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function reset($id, $code)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->resetPassword($id, $code);

        if( $result['success'] )
        {
            Event::fire('user.newpassword', array(
				'email' => $result['mailData']['email'],
				'newPassword' => $result['mailData']['newPassword']
			));

            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::route('home');
        }
	}

	/**
	 * Process a password change request
	 * @param  int $id 
	 * @return redirect     
	 */
	public function change($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$data = Input::all();
		$data['id'] = $id;

		// Form Processing
        $result = $this->changePasswordForm->change( $data );

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::route('home');
        } 
        else 
        {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@edit', array($id))
                ->withInput()
                ->withErrors( $this->changePasswordForm->errors() );
        }
	}

	/**
	 * Process a suspend user request
	 * @param  int $id 
	 * @return Redirect     
	 */
	public function suspend($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		// Form Processing
        $result = $this->suspendUserForm->suspend( Input::all() );

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('UserController@suspend', array($id))
                ->withInput()
                ->withErrors( $this->suspendUserForm->errors() );
        }
	}

	/**
	 * Unsuspend user
	 * @param  int $id 
	 * @return Redirect     
	 */
	public function unsuspend($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->unSuspend($id);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::to('users');
        }
	}

	/**
	 * Ban a user
	 * @param  int $id 
	 * @return Redirect     
	 */
	public function ban($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

		$result = $this->user->ban($id);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::to('users');
        }
	}

	public function unban($id)
	{
        if(!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }
        
		$result = $this->user->unBan($id);

        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::to('users');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::to('users');
        }
	}

/***** zelf toegevoegd aan UserController ******/
	public function changepassword($id)
	{
		return View::make('users.changepassword', array('id' => $id));
	}
	
	public function storepassword()
	{
		$data = Input::all();
		
		// is het oude wachtwoord het goede?
		// haal eerst het e-mail adres op
		// en dan authenticate
		$thisuser = DB::table('users')->where('id',$data['adminbeheer'])->first();
		$email = $thisuser->email;
		$s = null;
		try
		{
			$credentials = array('email' => $email, 'password' => $data['oudww']);
			$user = Sentry::authenticate($credentials, false);
		}
		
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    $s = 'je moet aangemeld zijn';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		    $s = 'Je moet een wachtwoord invullen';
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
		    $s = 'Het wachtwoord is verkeerd';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $s =  'verkeerde gebruiker!';
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
		    $s = 'De gebruiker werd (nog) niet geactiveerd';
		}
		// The following is only required if the throttling is enabled
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
		    $s = 'Deze gebruiker is geschorst';
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
		    $s = 'Deze gebruiker werd verbannen';
		}		

		
	
		// zo ja, zijn de strings ww1 en ww2 gelijk aan elkaar
		$gelijk = null;
		if ( $data['ww1'] != $data['ww2']) $gelijk = "De beide nieuwe wachtwoorden zijn niet gelijk";
		
		// ww1 moet minstens 6 karakters lang zijn
		$kleiner = null;
		if (strlen($data['ww1']) < 6) $kleiner = "het nieuwe wachtwoord moet minstens 6 karakters tellen!";
			
		$errorrij = null;
		if (isset($s)) $errorrij['oudww'] = $s;
		if (isset($gelijk)) $errorrij['ww1'] = $gelijk;
		if (isset($kleiner)) $erroraij['ww2'] = $kleiner;
		
		if ($errorrij)
		{
			return Redirect::route('changepassword', array('id' => $data['adminbeheer']))->withErrors($errorrij);
		} else {
			// zo ja, dan is ww1 het nieuwe wachtwoord!!!
			$user = Sentry::getUserProvider()->findById($data['adminbeheer']);
			$user->password = $data['ww1'];
			$user->save();
			return Redirect::to('passwdsuccess');
		}
	}

    public function changeprofile($id)
	{
		 
		$user = User::find($id);
		if ($user == null || !is_numeric($id))
		{
			return \App::abort(404);
		}

        $currentGroups = $user->getGroups()->toArray();
        $userGroups = array();
        foreach ($currentGroups as $group) {
        	array_push($userGroups, $group['name']);
        }
        $allGroups = $this->group->all();		
		
//		var_dump($user);die("tot hier"); 
		return View::make('users.changeprofile', array('id' => $id))->with('user', $user)->with('userGroups', $userGroups)->with('allGroups', $allGroups);
	}
	
	public function storeprofile()
	{
		$data = Input::all();
		$user = new User;
		$result = $user->myValidate($data);
		if (!$result)
		{
			$messages = $user->errors();
			$temp = "<br />".implode("<br />", $messages->all());			
			Session::flash('error', $temp);

			$id = $data['adminbeheer'];
			return Redirect::route('changeprofile',array($id))->withInput()->withErrors($user->errors());
		}

		// zoek de huidige user !!!
		$userExtra = UserExtra::where('user_id', $data['adminbeheer'])->firstOrFail();
		
		$result = $userExtra->validate($data);
		if (!$result)
		{
			$messages = $userExtra->errors();
			$temp = "<br />".implode("<br />", $messages->all());			
			Session::flash('error', $temp);
			
			$id = $data['adminbeheer'];
			return Redirect::route('changeprofile',array($id))->withInput()->withErrors($user->errors());
		}
		
		// Als we hier nu gewoon updaten, dan krijg je een foutmelding omdat de email unique moet zijn en
		// deze is misschien niet gewijzigd en daarom als niet unique gezien. Dus eerst kijken of je e-mail gewijzigd is
		$userdata = array(
		   'first_name' => $data['first_name'],
		   'last_name' => $data['last_name'],
		);
		
		$user = User::find($data['adminbeheer']);
		$emailchanged = $user->email != $data['email'];

		if ($emailchanged)
		{
			$userdata['email'] = $data['email'];
		} 
		$success = $user->update($userdata);
		if (!$success)
		{
			$messages = "Ik was niet in staat om je voornaam, familienaam of je e-mail te bewaren";
			Session::flash('error', $messages);
			
			$id = $data['adminbeheer'];
			return Redirect::route('changeprofile',array($id))->withInput();			
		}
		 
		$userExtradata = array(
			'title' => $data['title'],
			'street' => $data['street'],
			'housenr' => $data['housenr'],
			'zip' => $data['zip'],
			'city' => $data['city'],
			'country' => $data['country'],
			'birthdate' => $data['birthdate'],
			'phone' => $data['phone'],
			'gsm' => $data['gsm'],
			'diploma' => $data['diploma'],
			'position' => $data['position'],
			'workplace' => $data['workplace'],
		);
		
		$success = $userExtra->update($userExtradata);
		if (!$success)
		{
			$messages = "Ik was niet in staat om je data te bewaren";
			Session::flash('error', $messages);
			
			$id = $data['adminbeheer'];
			return Redirect::route('changeprofile',array($id))->withInput();				
		}
		
		return Redirect::route('inhoud');
	}
}

	
