<?php

class RemindersController extends Controller {
    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function getRemind()
    {
        return View::make('app.password.remind');
    }
 
    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function postRemind()
    {
        //validate email entered here 
        $rules = array(
            'email'     => 'required|email',
        );
 
        $validator = Validator::make(Input::all(), $rules);
 
        if ($validator->fails()) 
        {
            return Redirect::to('password/remind')
                ->withErrors($validator) 
                ->withInput(Input::except('password'));
        }else
        {
            $response = Password::remind(Input::only('email'), function($message) {
            	// I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
				$user = array(
					'email' => Input::get('email')
				);

				// the data that will be passed into the mail view blade template
				$data = array('token' => Input::get('email'));

                $message->to($user['email'])->subject('Ihr neues Passwort');
            });

            switch ($response) {
                case Password::INVALID_USER:
                    return Redirect::back()->with('error', Lang::get($response));

                case Password::REMINDER_SENT:
                    return Redirect::back()->with('status', Lang::get($response));
            }
        }
 
    }
 
    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) App::abort(404);
 
        return View::make('app.password.reset')->with('token', $token);
    }
 
    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $credentials = Input::only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $response = Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();
        });
        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));
            case Password::PASSWORD_RESET:
                return Redirect::to('/');
        }
    }
 
}

?>