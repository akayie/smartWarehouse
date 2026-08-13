<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationMoneyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
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
            'donation_id' => [
                'required',
                'exists:donations,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999999.99',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'donation_id.required' =>
                'Please select a donation.',

            'donation_id.exists' =>
                'Selected donation does not exist.',

            'amount.required' =>
                'Please enter the donation amount.',

            'amount.numeric' =>
                'Amount must be a number.',

            'amount.min' =>
                'Amount must be greater than 0.',

            'currency.required' =>
                'Please select a currency.',
        ];
    }
}
