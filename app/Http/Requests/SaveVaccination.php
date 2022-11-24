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
            'guardian_name' => "required|alpha_spaces|max:150",
            'dob' => "required",
            'age' => "required|numeric|digits:2",
            'height' => "required|numeric|max:150",
            'weight' => "required|numeric|max:150",
            'bmi' => "required|numeric|max:150",
            'vaccination_id' => "required",
        ];

        return $rules;
    }
}
