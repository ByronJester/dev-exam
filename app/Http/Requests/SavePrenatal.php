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
            'provider_name' => "required|alpha_spaces|max:150",
            'npi' => "required|numeric|max:150",
            'billing_id' => "required|numeric|max:150",
            'tax_id' => "required|numeric|digits:9",
            'provider_address' => "required|max:150",
            'city' => "required|max:150",
            'town' => "required|max:150",
            'zip' => "required|numeric|digits:4",
            'provider_phone' => "required|numeric|digits:11",
            'provider_fax' => "required|max:150",
            'member_name' => "required|alpha_spaces|max:150",
            'member_id' => "required|numeric|max:150",
            'dob' => "required",
            'member_address' => "required|max:150",
            'member_phone' => "required|numeric|digits:11",
            'member_city' => "required|alpha_spaces|max:150",
            'member_town' => "required|alpha_spaces|max:150",
            'member_zip' => "required|numeric|digits:4|max:150",
            'lmp' => "required",
            'g' => "required",
            'p' => "required",
            'edd' => "required",
            'first_prenatal' => "required",
            'recent_prenatal' => "required",
            'type_of_last_delivery' => "required|string|max:150",
            'last_delivery' => "required|max:150",
            'delivery_facility' => "required|max:150",
            'behavioral_risks' => "sometimes|nullable|max:150",
            'pyschological_risks' => "sometimes|nullable|max:150",
            'medical_risks' => "sometimes|nullable|max:150",
            'obstetrics_risks' => "sometimes|nullable|max:150",
            'ob_gyn' => "required|alpha_spaces|max:150",
            'date' => "required"
        ];

        return $rules;
    }
}
