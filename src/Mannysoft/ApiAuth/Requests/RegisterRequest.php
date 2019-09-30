<?php

namespace Mannysoft\ApiAuth\Requests;

use Mannysoft\ApiFormRequest\ApiFormRequest;

class RegisterRequest extends ApiFormRequest
{
    // Whether you want to use other status code other than 422.
    // You can use 400 also
    protected $statusCode = 422;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (config('api-auth.register_validation')) {
            return config('api-auth.register_validation');
        }
        
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];
    }
}
