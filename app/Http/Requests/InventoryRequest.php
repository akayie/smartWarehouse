<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => [
                'required',
                'integer',
                'exists:warehouses,id',
            ],

            'item_id' => [
                'required',
                'integer',
                'exists:items,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'expiry_date' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'warehouse_id.required' => 'Warehouse ကို ရွေးချယ်ပေးပါ။',
            'warehouse_id.exists'   => 'ရွေးချယ်ထားသော Warehouse မရှိပါ။',

            'item_id.required' => 'ပစ္စည်းကို ရွေးချယ်ပေးပါ။',
            'item_id.exists'   => 'ရွေးချယ်ထားသော ပစ္စည်းမရှိပါ။',

            'quantity.required' => 'Quantity ထည့်ပေးပါ။',
            'quantity.integer'  => 'Quantity သည် ကိန်းဂဏန်းဖြစ်ရပါမည်။',
            'quantity.min'      => 'Quantity သည် 0 ထက်မနည်းရပါ။',

            'expiry_date.date' => 'Expiry Date မှန်ကန်သော Date ဖြစ်ရပါမည်။',
        ];
    }
}
