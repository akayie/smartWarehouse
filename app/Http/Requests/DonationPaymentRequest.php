<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationPaymentRequest extends FormRequest
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

            'donation_money_id' => [
                'required',
                'exists:donation_money,id',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'account_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999999.99',
            ],

            'proof' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'status' => [
                'required',
                'in:Pending,Completed,Failed,Cancelled',
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

            'donation_money_id.required' =>
                'Please select a money donation.',

            'donation_money_id.exists' =>
                'Selected money donation does not exist.',

            'payment_method.required' =>
                'Please select a payment method.',

            'payment_date.required' =>
                'Please select payment date.',

            'payment_date.date' =>
                'Payment date must be a valid date.',

            'amount.required' =>
                'Please enter payment amount.',

            'amount.numeric' =>
                'Payment amount must be a number.',

            'amount.min' =>
                'Payment amount must be greater than 0.',

            'proof.file' =>
                'Payment proof must be a valid file.',

            'proof.mimes' =>
                'Proof must be JPG, JPEG, PNG, or PDF.',

            'proof.max' =>
                'Proof file must not exceed 5 MB.',

            'status.required' =>
                'Please select payment status.',

            'status.in' =>
                'Invalid payment status.',
        ];
    }
}
