<?php

namespace Mannysoft\ApiAuth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7;
use Socialite;
use App\User;
use Mannysoft\ApiAuth\Traits\Token;
use Mannysoft\ApiAuth\Requests\RegisterRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;


use Illuminate\Support\Facades\Password;

class AuthenticateController extends Controller
{
    use SendsPasswordResetEmails;
    use Token;

    /**
     * $appId
     * @var [int]
     */
    protected $appId;

    /**
     * [$appSecret description]
     * @var [string]
     */
    protected $appSecret;

    /**
    * [$tokenExchangeUrl description]
    * @var [type]
    */
    protected $tokenExchangeUrl;

    /**
    * [$endPointUrl description]
    * @var [type]
    */
    protected $endPointUrl;

    /**
    * [$userAccessToken description]
    * @var [type]
    */
    public $userAccessToken;

    /**
    * [$refreshInterval description]
    * @var [type]
    */
    protected $refreshInterval;

    /**
    * [__construct description]
    */
    public function __construct()
    {
      config(['auth.defaults.guard' => 'api']);
      $this->appId            = config('accountkit.app_id');
      $this->client           = new Client();
      $this->appSecret        = config('accountkit.app_secret');
      $this->endPointUrl      = config('accountkit.end_point');
      $this->tokenExchangeUrl = config('accountkit.tokenExchangeUrl');
    }

    public function register(RegisterRequest $request)
    {
      $data = request()->all();
      $data['password'] = bcrypt(request('password'));
      $data['name'] = '';
      User::create($data);
      return $this->authenticate($request);
    }

    public function authenticate(Request $request)
    {
        if (config('api-auth.auth') == 'jwt') {
            $credentials = request(['email', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['message' => 'Invalid Email or Password.'], 401);
            }
            
            if (function_exists('storeActivity')) {
                storeActivity('user', 'User Login', 'Logged In');
            }

            return $this->respondWithToken($token);
        }

        // passport
        return $this->getToken(request(config('api-auth.username')), request('password'));
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function loginFacebook(Request $request)
    {
        try {
            $fb = Socialite::driver('facebook')->userFromToken(request('access_token'));
        } catch (RequestException $e){
            return response()->json(['message' => 'Invalid access token.'], 401);
        }

        $user = User::where('fb_id', $fb->getId())->first();

        if ($fb->getEmail()) {
          $user = User::where('email', $fb->getEmail())->first();
        }

        $password = str_random(8);

        if ($user) {
            // Save and login the user           
            $user->fb_image_url = $fb->getAvatar();
            $user->password = bcrypt($password);
            $user->first_name = request('first_name');
            $user->last_name = request('last_name');
            $user->save();

            ///return $this->getToken($user->email, $password);
            $request->request->add(['password' => $password]);

            return $this->authenticate($request);
        }

        $user = new User;
        $user->name = $fb->getName();
        $user->email = $fb->getEmail();
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        //$user->username = $fb->getEmail();
        $user->fb_id = $fb->getId();
        $user->password = bcrypt($password);
        $user->fb_image_url = $fb->getAvatar();
        $user->save();

        $request->request->add(['password' => $password]);

        return $this->authenticate($request);

        $data = $this->getToken($fb->getEmail(), $password);

        return $data;
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     */
    public function logout(Request $request)
    {
        $tokens = $request->user()->tokens;

        foreach ($tokens as $token) {
            $token->revoked = true;
            $token->save();
        }

        return response()->json(null, 200);
 
        return $request->user()->tokens;
    }

    public function accountKit(Request $request)
    {
        // we require access token here to get the user details
        $url = $this->tokenExchangeUrl.'grant_type=authorization_code'.
          '&code='. $request->get('code').
          "&access_token=AA|$this->appId|$this->appSecret";

        $apiRequest = $this->client->request('GET', $url);

        $body = json_decode($apiRequest->getBody());

        $this->userAccessToken = $body->access_token;

        $this->refreshInterval = $body->token_refresh_interval_sec;

        return $this->getData();
    }

    public function getData()
    {
        $request = $this->client->request('GET', $this->endPointUrl.$this->userAccessToken);

        $data = json_decode($request->getBody());

        $userId = $data->id;

        $userAccessToken = $this->userAccessToken;

        $refreshInterval = $this->refreshInterval;

        $phone = isset($data->phone) ? $data->phone->number : '';

        $email = isset($data->email) ? $data->email->address : '';
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['status' => 'success', 'message' => trans($response)], 200);
        }

        return response()->json(['status' => 'failed', 'message' => trans($response)], 400);
        
    }

}
