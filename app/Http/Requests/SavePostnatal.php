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
            'hospital_name' => "required",
            'clients_name' => "required",
            'address' => "required",
            'occupation' => "required",
            'type_of_delivery' => "required",
            'mode_of_delivery' => "required",
            'total_hours_of_labor' => "required",
            'postnatal_day' => "required",
            'register_no' => "required",
            'doa' => "required",
            'religion' => "required",
            'gestational_age' => "required",
            'lmp' => "required",
            'new_born_sex' => "required",
            'days_of_hospital_stay' => "required",
            'age' => "required",
            'education' => "required",
            'obstetrical_score' => "required",
            'chief_complain' => "required",
            'edd' => "required",
            'newborn_weight' => "required",
            'bad_habits' => "required",
            'elimination_pattern' => "required",
            'activity_and_exercise' => "required",
            'menarche_age' => "required",
            'period' => "required",
            'nutritional_pattern' => "required",
            'sleeping_pattern' => "required",
            'cycle' => "required",
            'amount_of_blood_loss' => "required",
            'duration_of_marriage' => "required",
            'family_planning_method_adopted' => "required",
            'diseases' => "required",
            'genitics_condition' => "required",
            'gravidity' => "required",
            'parity' => "required",
            'miscarriages' => "required",
            'terminations' => "required",
            'previous_pregnancy' => "required",
            'length_of_pregnancy' => "required",
            'induction' => "required",
        ];

        return $rules;
    }
}
