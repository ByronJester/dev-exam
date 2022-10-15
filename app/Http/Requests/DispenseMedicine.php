<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DispenseMedicine extends FormRequest
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
            'medicine_id' => "required",
            'quantity' => "required|numeric|min:1",
        ];

        return $rules;
    }
}
