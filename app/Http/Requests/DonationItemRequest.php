<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationItemRequest extends FormRequest
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

            'item_id' => [
                'required',
                'exists:items,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'donation_id.required' =>
                'Please select a donation.',

            'donation_id.exists' =>
                'Selected donation does not exist.',

            'item_id.required' =>
                'Please select an item.',

            'item_id.exists' =>
                'Selected item does not exist.',

            'quantity.required' =>
                'Please enter quantity.',

            'quantity.integer' =>
                'Quantity must be a whole number.',

            'quantity.min' =>
                'Quantity must be at least 1.',
        ];
    }
}
