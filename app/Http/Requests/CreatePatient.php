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
            'place_id' => "required",
            'name' => "required|alpha_spaces",
            'phone' => "required|numeric|digits:11",
            'dob' => "required",
            'age' => "required|numeric|min:1|max:120",
            'gender' => "required|alpha_spaces",
            'civil_status' => "required|alpha_spaces",
            'philhealth' => "nullable|numeric|digits:12",
            'contact_person' => "required|alpha_spaces",
            'contact_person_address' => "required|string",
            'contact_person_phone' => "required|numeric|digits:11",
            'diagnosis' => "nullable"
        ];

        return $rules;
    }
}
