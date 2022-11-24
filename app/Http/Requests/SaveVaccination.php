<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveVaccination extends FormRequest
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
            'guardian_name' => "required|alpha_spaces",
            'dob' => "required",
            'age' => "required|numeric|digits:2",
            'height' => "required|numeric",
            'weight' => "required|numeric",
            'bmi' => "required|numeric",
            'vaccination_id' => "required",
        ];

        return $rules;
    }
}
