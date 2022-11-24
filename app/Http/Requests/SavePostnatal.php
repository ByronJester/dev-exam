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
            'hospital_name' => "required|alpha_spaces|max:150",
            'clients_name' => "required|alpha_spaces|max:150",
            'address' => "required|max:150",
            'occupation' => "required|alpha_spaces|max:150",
            'type_of_delivery' => "required|alpha_spaces|max:150",
            'mode_of_delivery' => "required|alpha_spaces|max:150",
            'total_hours_of_labor' => "required|numeric|max:150",
            'postnatal_day' => "required|max:150",
            'register_no' => "required|numeric|max:150",
            'doa' => "required",
            'religion' => "required|alpha_spaces|max:150",
            'gestational_age' => "required|numeric|max:150",
            'lmp' => "required",
            'new_born_sex' => "required|max:150",
            'days_of_hospital_stay' => "required|numeric|max:150",
            'age' => "required|numeric|max:150",
            'education' => "required|string|max:150",
            'obstetrical_score' => "required|numeric|max:150",
            'chief_complain' => "required|alpha_spaces|max:150",
            'edd' => "required",
            'newborn_weight' => "required|numeric|max:150",
            'bad_habits' => "required|alpha_spaces|max:150",
            'elimination_pattern' => "required|max:150",
            'activity_and_exercise' => "required|alpha_spaces|max:150",
            'menarche_age' => "required|numeric|max:150",
            'period' => "required",
            'nutritional_pattern' => "required|max:150",
            'sleeping_pattern' => "required|max:150",
            'cycle' => "required",
            'amount_of_blood_loss' => "required|max:150",
            'duration_of_marriage' => "required|max:150",
            'family_planning_method_adopted' => "required|max:150",
            'diseases' => "required|alpha_spaces|max:150",
            'genitics_condition' => "required|alpha_spaces|max:150",
            'gravidity' => "required|numeric|max:150",
            'parity' => "required|numeric|max:150",
            'miscarriages' => "required|numeric|max:150",
            'terminations' => "required|numeric|max:150",
            'previous_pregnancy' => "required|max:150",
            'length_of_pregnancy' => "required|max:150",
            'induction' => "required|max:150",
        ];

        return $rules;
    }
}
