<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePrenatal extends FormRequest
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
            'patient_id' => "required",
            'date_today' => "required",
            'provider_name' => "required|alpha_spaces",
            'npi' => "required|numeric",
            'billing_id' => "required|numeric",
            'tax_id' => "required|numeric|digits:9",
            'provider_address' => "required",
            'city' => "required",
            'town' => "required",
            'zip' => "required|numeric|digits:4",
            'provider_phone' => "required|numeric|digits:11",
            'provider_fax' => "nullable",
            'member_name' => "required|alpha_spaces",
            'age' => "required|numeric",
            'dob' => "required",
            'member_address' => "required",
            'member_phone' => "required|numeric|digits:11",
            'member_city' => "required|alpha_spaces",
            'member_town' => "required|alpha_spaces",
            'member_zip' => "required|numeric|digits:4",
            'lmp' => "required",
            'g' => "required",
            'p' => "required",
            'edd' => "required",
            'first_prenatal' => "required",
            'recent_prenatal' => "required",
            'type_of_last_delivery' => "required|string",
            'last_delivery' => "required",
            'delivery_facility' => "required",
            'behavioral_risks' => "sometimes|nullable",
            'pyschological_risks' => "sometimes|nullable",
            'medical_risks' => "sometimes|nullable",
            'obstetrics_risks' => "sometimes|nullable",
            'ob_gyn' => "required|alpha_spaces",
            'date' => "required"
        ];

        return $rules;
    }
}
