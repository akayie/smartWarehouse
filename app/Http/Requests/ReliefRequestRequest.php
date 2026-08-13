<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReliefRequestRequest extends FormRequest
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

            'disaster_id' => [
                'required',
                'exists:disasters,id',
            ],

            'requested_by' => [
                'required',
                'exists:users,id',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'request_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:Pending,Approved,Rejected,Processing,Completed,Cancelled',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'disaster_id.required' =>
                'Please select a disaster.',

            'disaster_id.exists' =>
                'Selected disaster does not exist.',

            'requested_by.required' =>
                'Please select the requester.',

            'requested_by.exists' =>
                'Selected requester does not exist.',

            'location.required' =>
                'Please enter the request location.',

            'request_date.required' =>
                'Please select the request date.',

            'request_date.date' =>
                'Request date must be a valid date.',

            'status.required' =>
                'Please select request status.',

            'status.in' =>
                'Invalid request status.',
        ];
    }
}
