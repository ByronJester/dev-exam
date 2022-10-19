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
            'place_id' => "sometimes|required",
            'patient_id' => "sometimes|required",
            'quantity' => "required|integer|min:1",
        ];

        return $rules;
    }
}
