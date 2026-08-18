<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donation_type' => [
                'required',
                'in:Cash,Item,Both',
            ],
            'warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],
            'donation_date' => [
                'required',
                'date',
            ],
            'amount' => [
                'nullable',
                'required_if:donation_type,Cash,Both',
                'numeric',
                'min:1',
            ],
            'payment_method' => [
                'nullable',
                'required_if:donation_type,Cash,Both',
                'string',
            ],
            'transaction_reference' => [
                'nullable',
                'string',
            ],
            'note' => [
                'nullable',
                'string',
            ],
            'items' => [
                'nullable',
                'array',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'donation_type.required' => 'လှူဒါန်းမှုအမျိုးအစား (donation_type) ရွေးချယ်ပေးပါရန်။',
            'warehouse_id.required'  => 'လက်ခံမည့် ဂိုဒေါင် ရွေးချယ်ပေးပါရန်။',
            'amount.required_if'     => 'ငွေသားလှူဒါန်းမှုအတွက် ပမာဏ ဖြည့်စွက်ပေးပါရန်။',
        ];
    }
}
