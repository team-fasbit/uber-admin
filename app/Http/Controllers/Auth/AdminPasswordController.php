<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class AdminPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after successful change of password.
     *
     * @var string
     */

    protected $guard = 'admin';

    protected $broker = 'admins';

    protected $redirectTo = '/admin';

    /**
     * The password reset request view that should be used.
     *
     * @var string
     */

    protected $linkRequestView = 'admin.auth.passwords.email';

    /**
     * The password reset view that should be used.
     *
     * @var string
     */

    protected $resetView = 'admin.auth.passwords.reset';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function getCredentials(Request $request, $email, $reset_for )
    {
        if($reset_for == "corporate"){
        //dd('$reset_for');
            return redirect('corporate/password/reset')->with('email' ,$email);
        }
        else if($reset_for == "manager"){
        
            return redirect('manager/password/reset')->with('email' ,$email);
        }
        else if($reset_for == "provider"){
        
            return redirect('provider/password/reset')->with('email' ,$email);
        }
        else if($reset_for == "user"){
        
            return redirect('user/password/reset')->with('email' ,$email);
        }
        else{

            return redirect('admin/password/reset')->with('email' ,$email);
        }
    }
}

