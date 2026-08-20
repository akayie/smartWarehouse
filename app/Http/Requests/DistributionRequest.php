<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'request_id' => [
                'nullable',
                'exists:relief_requests,id',
            ],

            'warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],

            'handled_by' => [
                'nullable',
                'exists:users,id',
            ],

            'distribution_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | DONATION FUNDING
            |--------------------------------------------------------------------------
            */

            'funding_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],

            /*
            |--------------------------------------------------------------------------
            | ITEMS
            |--------------------------------------------------------------------------
            */

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.item_id' => [
                'required',
                'exists:items,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:1',
            ],

            'items.*.expiry_date' => [
                'nullable',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'warehouse_id.required' =>
                'Warehouse ရွေးချယ်ရန်လိုအပ်ပါသည်။',

            'distribution_date.required' =>
                'Distribution Date ထည့်ရန်လိုအပ်ပါသည်။',

            'funding_amount.numeric' =>
                'Funding Amount သည် ဂဏန်းဖြစ်ရပါမည်။',

            'funding_amount.min' =>
                'Funding Amount သည် 0 ထက်ငယ်၍မရပါ။',

            'items.required' =>
                'အနည်းဆုံး Item တစ်ခုထည့်ရပါမည်။',

            'items.min' =>
                'အနည်းဆုံး Item တစ်ခုထည့်ရပါမည်။',

            'items.*.item_id.required' =>
                'Item ရွေးချယ်ရန်လိုအပ်ပါသည်။',

            'items.*.quantity.required' =>
                'Quantity ထည့်ရန်လိုအပ်ပါသည်။',

            'items.*.quantity.min' =>
                'Quantity သည် 1 ထက်ကြီးရပါမည်။',
        ];
    }
}
