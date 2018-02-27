<?php

namespace Mannysoft\ApiAuth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
/**
 * @resource Authentication
 *
 * Auth
 */
class AuthenticateController extends Controller
{

    public function authenticate(Request $request)
    {
        return $this->getToken(request(config('api-auth.username')), request('password'));

        return response()->json(['message' => 'Invalid Email or Password.'], 401);
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
 
        return $request->user()->tokens;
    }

    // Return the token
    public function getToken($email, $password)
    {
        $http = new Client;

        $response = $http->post(request()->root() .'/oauth/token', [
            'form_params' => [
                'grant_type' => config('auth.app_oauth.grant_type'),
                'client_id' => config('auth.app_oauth.client_id'),
                'client_secret' => config('auth.app_oauth.client_secret'),
                'username' => $email,
                'password' => $password,
                'scope' => config('auth.app_oauth.scope'),
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

}