<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatient extends FormRequest
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
            'place_id' => "required|max:150",
            'name' => "required|alpha_spaces|max:150",
            'phone' => "required|numeric|digits:11|max:150",
            'dob' => "required|max:150",
            'age' => "required|numeric|max:150",
            'gender' => "required|alpha_spaces|max:150",
            'civil_status' => "required|alpha_spaces|max:150",
            'philhealth' => "required|numeric|digits:12",
            'contact_person' => "required|alpha_spaces|max:150",
            'contact_person_address' => "required|string|max:150",
            'contact_person_phone' => "required|numeric|digits:11"
        ];

        return $rules;
    }
}
