<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterAccount extends FormRequest
{
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
        $rules = [
            'first_name' => "required|alpha_spaces",
            'middle_name' => "nullable|alpha_spaces",
            'last_name' => "required|alpha_spaces",
            'phone' => "required|numeric|digits:11|unique:users,phone",
            'email' => "required|unique:users,email|email:rfc,dns",
            'user_type' => "required",
            'work_address' => 'sometimes|required'
        ];

        return $rules;
    }
}