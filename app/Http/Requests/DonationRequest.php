<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
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
            'donor_id' => [
                'required',
                'exists:donors,id',
            ],

            'warehouse_id' => [
                'nullable',
                'exists:warehouses,id',
            ],

            'donation_type' => [
                'required',
                'string',
                'max:100',
            ],

            'donation_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:Pending,Received,Cancelled',
            ],

            'note' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'donor_id.required' =>
                'Please select a donor.',

            'donor_id.exists' =>
                'Selected donor does not exist.',

            'warehouse_id.exists' =>
                'Selected warehouse does not exist.',

            'donation_type.required' =>
                'Please select donation type.',

            'donation_date.required' =>
                'Please select donation date.',

            'status.required' =>
                'Please select donation status.',

            'status.in' =>
                'Invalid donation status.',
        ];
    }
}
