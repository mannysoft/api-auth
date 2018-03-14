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
use Mannysoft\ApiAuth\User;
use Mannysoft\ApiAuth\Requests\ResetPasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Support\Facades\Password;

use Mannysoft\ApiAuth\Traits\Token;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;
    use Token;

    public function reset(ResetPasswordRequest $request)
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return $this->getToken(request(config('api-auth.username')), request('password'));

            return response()->json(['status' => 'success', 'message' => trans($response)], 200);
        }

        return response()->json(['status' => 'failed', 'message' => trans($response)], 400);
    }

}