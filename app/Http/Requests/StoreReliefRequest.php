<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReliefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'disaster_option' => [
                'required',
                'in:existing,new',
            ],
            'disaster_id' => [
                'nullable',
                'required_if:disaster_option,existing',
                'exists:disasters,id',
            ],
            'new_disaster_name' => [
                'nullable',
                'required_if:disaster_option,new',
                'string',
                'max:255',
            ],
            'new_disaster_type' => [
                'nullable',
                'required_if:disaster_option,new',
                'string',
                'max:255',
            ],
            'start_date' => [
                'nullable',
                'required_if:disaster_option,new',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'location' => [
                'required',
                'string',
                'max:255',
            ],
            'latitude' => [
                'nullable',
                'numeric',
            ],
            'longitude' => [
                'nullable',
                'numeric',
            ],
            'warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],
            'note' => [
                'nullable',
                'string',
            ],
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
        ];
    }

    public function messages(): array
    {
        return [
            'disaster_option.required' => 'မေးခွန်းဟောင်း သို့မဟုတ် အသစ် ရွေးချယ်ပေးပါ။',
            'disaster_id.required_if' => 'ဘေးအန္တရာယ်အမျိုးအစား ရွေးချယ်ပေးပါ။',
            'location.required' => 'တည်နေရာ ဖြည့်စွက်ပေးပါ။',
            'warehouse_id.required' => 'ဂိုဒေါင် ရွေးချယ်ပေးပါ။',
            'items.required' => 'အနည်းဆုံး ပစ္စည်းတစ်ခု တောင်းခံပေးပါ။',
        ];
    }
}
