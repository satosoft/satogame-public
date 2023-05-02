<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


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

    protected $username;

    /*
     * Create a new controller instance.
     *
     * @return void
     */

    
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }
    
    
    
    public function showLoginForm()
    {
        $pageTitle = "Login";
        //$activeTemplate = $this->activeTemplate;
        //$general = gs();

        //Log::info('The value of active template in logincontroller class is: ' . $this->activeTemplate); 
        //Log::info('The value of general in logincontroller class is: ' . $this->general); 
        //Log::info('The value of general in logincontroller class is: ' . $this->general); 

        //return view($this->activeTemplate . 'user.auth.login', compact('pageTitle','activeTemplate','general'));
        return view($this->activeTemplate . 'user.auth.login', compact('pageTitle'));
        //return view('templates.basic.user.auth.login', compact('pageTitle'));

    }
    
    public function login(Request $request)
    {

        $this->validateLogin($request);

        $request->session()->regenerateToken();

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

                // block the user from logging in for 5 minutes
            //$this->blockUser($request, 5);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            // if the user logs in successfully, clear their login attempts
            $this->clearLoginAttempts($request);
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    /*
    protected function blockUser(Request $request, $minutes)
    {
        $key = $this->throttleKey($request);
        $expiresAt = now()->addMinutes($minutes);

        // set the user as blocked in the cache
        Cache::put($key . ':blocked', true, $expiresAt);

        // clear the user's login attempts
        $this->clearLoginAttempts($request);
    }

    
    protected function isUserBlocked(Request $request)
    {   
    $key = $this->throttleKey($request);

    return Cache::has($key . ':blocked');
    }

    protected function clearUserBlock(Request $request)
    {
        $key = $this->throttleKey($request);

        Cache::forget($key . ':blocked');
    }

    protected function hasTooManyLoginAttempts(Request $request)
{
    if ($this->isUserBlocked($request)) {
        return true;
    }

    return $this->limiter()->tooManyAttempts(
        $this->throttleKey($request), $this->maxAttempts()
    );
}

    protected function incrementLoginAttempts(Request $request)
    {
        if ($this->isUserBlocked($request)) {
            return;
        }

        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes() *60
        );
    }

    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
        $this->clearUserBlock($request);
    }
    */

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

    }

    public function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return to_route('user.login')->withNotify($notify);
    }





    public function authenticated(Request $request, $user)
    {
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        return to_route('user.home');
    }

    


}

?>
