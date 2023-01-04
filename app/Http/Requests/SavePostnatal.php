<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePostnatal extends FormRequest
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
            'hospital_name' => "required|alpha_spaces",
            'clients_name' => "required|alpha_spaces",
            'address' => "required",
            'occupation' => "required|alpha_spaces",
            'type_of_delivery' => "required|alpha_spaces",
            'mode_of_delivery' => "required|alpha_spaces",
            'total_hours_of_labor' => "required|numeric",
            'postnatal_day' => "required",
            'register_no' => "required|numeric",
            'doa' => "required",
            'religion' => "required|alpha_spaces",
            'gestational_age' => "required|numeric",
            'lmp' => "required",
            'new_born_sex' => "required",
            'dob' => "required",
            'age' => "required|numeric",
            'education' => "required|string",
            'obstetrical_score' => "required|numeric",
            'chief_complain' => "nullable|alpha_spaces",
            'edd' => "required",
            'newborn_weight' => "required|numeric",
            'bad_habits' => "nullable|alpha_spaces",
            'elimination_pattern' => "nullable",
            'activity_and_exercise' => "nullable|alpha_spaces",
            'menarche_age' => "required|numeric",
            'period' => "required",
            'nutritional_pattern' => "nullable",
            'sleeping_pattern' => "nullable",
            'cycle' => "required",
            'amount_of_blood_loss' => "required",
            'duration_of_marriage' => "nullable",
            'family_planning_method_adopted' => "nullable",
            'diseases' => "nullable|alpha_spaces",
            'genitics_condition' => "nullable|alpha_spaces",
            'gravidity' => "required|numeric",
            'parity' => "required|numeric",
            'miscarriages' => "required|numeric",
            'terminations' => "required|numeric",
            'previous_pregnancy' => "required",
            'length_of_pregnancy' => "required",
            'induction' => "required",
        ];

        return $rules;
    }
}
