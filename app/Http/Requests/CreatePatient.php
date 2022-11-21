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
            'name' => "required|string",
            'phone' => "required|numeric",
            'dob' => "required",
            'age' => "required|numeric",
            'gender' => "required|string",
            'civil_status' => "required|string",
            'philhealth' => "required|string",
            'contact_person' => "required|string",
            'contact_person_address' => "required|string",
            'contact_person_phone' => "required|numeric"
        ];

        return $rules;
    }
}
