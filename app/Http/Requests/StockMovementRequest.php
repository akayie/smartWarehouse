<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementRequest extends FormRequest
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
            'item_id' => [
                'required',
                'exists:items,id',
            ],

            'warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],

            'type' => [
                'required',
                'in:IN,OUT,TRANSFER',
            ],

            // Transfer ပြုလုပ်ချိန်မှသာ target_warehouse_id လိုအပ်မည်ဖြစ်ပြီး မူလ warehouse နှင့် မတူရပါ
            'target_warehouse_id' => [
                'required_if:type,TRANSFER',
                'nullable',
                'exists:warehouses,id',
                'different:warehouse_id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'item_id.required' => 'Please select an item.',
            'item_id.exists' => 'Selected item does not exist.',

            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse does not exist.',

            'type.required' => 'Please select stock movement type.',
            'type.in' => 'Invalid stock movement type.',

            'target_warehouse_id.required_if' => 'Please select a target warehouse for transfer.',
            'target_warehouse_id.exists' => 'Selected target warehouse does not exist.',
            'target_warehouse_id.different' => 'Target warehouse must be different from source warehouse.',

            'quantity.required' => 'Please enter quantity.',
            'quantity.integer' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be at least 1.',

            'reference.max' => 'Reference cannot exceed 255 characters.',
        ];
    }
}
