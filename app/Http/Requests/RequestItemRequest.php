<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestItemRequest extends FormRequest
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

            'request_id' => [
                'required',
                'exists:relief_requests,id',
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
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'request_id.required' =>
                'Please select a relief request.',

            'request_id.exists' =>
                'Selected relief request does not exist.',

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
