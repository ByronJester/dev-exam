<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDeworming extends FormRequest
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
            'guardian_name' => "required|string",
            'dob' => "required",
            'age' => "required|numeric",
            'height' => "required|numeric",
            'weight' => "required|numeric",
            'bmi' => "required|numeric",
            'deworming_medication' => "required",
        ];

        return $rules;
    }
}
