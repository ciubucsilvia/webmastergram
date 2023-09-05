<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAuth;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();

        // $this->maxAttempts = '1';
        // $this->decayMinutes = '30';
    }

    protected function findUsername()
    {
        $login = request()->input('login');
        
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        request()->merge([$fieldType => $login]);
        
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $oAuthUser = Socialite::driver($provider)->user();
        
        $oAuthUser = $this->findOrCreateUser($oAuthUser, $provider);

        Auth::login($oAuthUser, true);
     
        return redirect($this->redirectTo);
    }

    protected function findOrCreateUser($oAuthUser, $provider)
    {
        $existingOAuth = SocialAuth::where('provider_name', $provider)
        ->where('provider_id', $oAuthUser->getId())
        ->first();

        if ($existingOAuth) {
            return $existingOAuth->user;
        } else {
            $user = User::whereEmail($oAuthUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $oAuthUser->getEmail(),
                    'name'  => $oAuthUser->getName(),
                    'username'  => $oAuthUser->getName(),
                ]);
            }

            $user->oauth()->create([
                'provider_id'   => $oAuthUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;
        }
    }
}
