<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                'in:Active,Completed,Cancelled',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' =>
                'Please enter disaster name.',

            'type.required' =>
                'Please select disaster type.',

            'location.required' =>
                'Please enter disaster location.',

            'start_date.required' =>
                'Please select start date.',

            'end_date.after_or_equal' =>
                'End date must be after or equal to start date.',

            'status.required' =>
                'Please select disaster status.',

            'status.in' =>
                'Invalid disaster status.',
        ];
    }
}
