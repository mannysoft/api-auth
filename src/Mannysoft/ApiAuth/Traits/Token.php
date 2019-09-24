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
        $authUrl = config('api-auth.auth_url') ?? request()->root();
        try {
            $response = $http->post($authUrl .'/oauth/token', [
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
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                if ($response->getStatusCode() == 401) {
                    $data = json_decode($response->getBody()->getContents(), true);

                    return response()->json($data, $response->getStatusCode());
                }
            }
        } catch (ClientException $e) {
            
        }

        return response()->json(['message' => 'Invalid Email or Password.'], 401);
    }

}
