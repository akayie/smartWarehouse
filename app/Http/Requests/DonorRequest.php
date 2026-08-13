<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonorRequest extends FormRequest
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

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
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
                'Please enter donor name.',

            'name.max' =>
                'Donor name cannot exceed 255 characters.',

            'phone.max' =>
                'Phone number cannot exceed 30 characters.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.max' =>
                'Email cannot exceed 255 characters.',

            'address.max' =>
                'Address cannot exceed 1000 characters.',
        ];
    }
}
