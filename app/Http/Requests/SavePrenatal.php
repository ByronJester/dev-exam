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
            'provider_name' => "required",
            'npi' => "required",
            'billing_id' => "required",
            'tax_id' => "required",
            'provider_address' => "required",
            'city' => "required",
            'town' => "required",
            'zip' => "required",
            'provider_phone' => "required",
            'provider_fax' => "required",
            'member_name' => "required",
            'member_id' => "required",
            'dob' => "required",
            'member_address' => "required",
            'member_phone' => "required",
            'member_city' => "required",
            'member_town' => "required",
            'member_zip' => "required",
            'lmp' => "required",
            'g' => "required",
            'p' => "required",
            'edd' => "required",
            'first_prenatal' => "required",
            'recent_prenatal' => "required",
            'type_of_last_delivery' => "required",
            'last_delivery' => "required",
            'delivery_facility' => "required",
            'behavioral_risks' => "nullable",
            'pyschological_risks' => "nullable",
            'medical_risks' => "nullable",
            'obstetrics_risks' => "nullable",
            'ob_gyn' => "required",
            'date' => "required"
        ];

        return $rules;
    }
}
