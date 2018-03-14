<?php

namespace Mannysoft\ApiAuth\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7;

Trait Token
{
    
    public function authenticate(Request $request)
    {
        return $this->getToken(request(config('api-auth.username')), request('password'));
    }


    // Return the token
    public function getToken($email, $password)
    {
        $http = new Client;
        try {
            $response = $http->post(request()->root() .'/oauth/token', [
                'form_params' => [
                    'grant_type' => config('api-auth.app_oauth.grant_type'),
                    'client_id' => config('api-auth.app_oauth.client_id'),
                    'client_secret' => config('api-auth.app_oauth.client_secret'),
                    'username' => $email,
                    'password' => $password,
                    //'scope' => config('api-auth.app_oauth.scope'),
                    'scope' => '*'
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            // $this->errorMessage = Psr7\str($e->getResponse());
            // $statusCode = $e->getResponse()->getStatusCode();
            // $this->body = $e->getResponse()->getBody();
            //dd(Psr7\str($e->getResponse()));

        } catch (ClientException $e) {
            //dd(1);
        }

        return response()->json(['message' => 'Invalid Email or Password.'], 401);
    }

}