<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReliefRequest extends FormRequest
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
            'warehouse_id' => [
                'required',
                'exists:warehouses,id',
            ],

            'disaster_option' => [
                'required',
                'in:existing,new',
            ],

            'disaster_id' => [
                'required_if:disaster_option,existing',
                'nullable',
                'exists:disasters,id',
            ],

            'new_disaster_name' => [
                'required_if:disaster_option,new',
                'nullable',
                'string',
                'max:255',
            ],

            'new_disaster_type' => [
                'required_if:disaster_option,new',
                'nullable',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required_if:disaster_option,new',
                'nullable',
                'date',
            ],

            // Added validation for name and phone number
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone_number' => [
                'required',
                'string',
                'max:50',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            // Added photo validation (nullable)
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            'note' => [
                'nullable',
                'string',
            ],

            // ပစ္စည်းစာရင်း (Items) အတွက် Validation Rules များ
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
            'warehouse_id.required' => 'ကျေးဇူးပြု၍ ဂိုဒေါင်ကို ရွေးချယ်ပါ။',
            'warehouse_id.exists' => 'ရွေးချယ်ထားသော ဂိုဒေါင် မရှိပါ။',

            'disaster_id.required_if' => 'ကျေးဇူးပြု၍ သဘာဝဘေးအန္တရာယ်ကို ရွေးချယ်ပါ။',
            'disaster_id.exists' => 'ရွေးချယ်ထားသော သဘာဝဘေးအန္တရာယ် မရှိပါ။',

            'new_disaster_name.required_if' => 'သဘာဝဘေးအန္တရာယ် အသစ်အမည် ထည့်သွင်းရန် လိုအပ်ပါသည်။',
            'new_disaster_type.required_if' => 'သဘာဝဘေးအန္တရာယ် အမျိုးအစားကို ထည့်သွင်းပါ။',
            'start_date.required_if' => 'စတင်သည့်ရက်စွဲကို ထည့်သွင်းပါ။',

            'name.required' => 'ကျေးဇူးပြု၍ အမည်ကို ထည့်သွင်းပါ။',
            'phone_number.required' => 'ကျေးဇူးပြု၍ ဖုန်းနံပါတ်ကို ထည့်သွင်းပါ။',

            'location.required' => 'ကျေးဇူးပြု၍ တည်နေရာကို ထည့်သွင်းပါ။',

            // Added custom messages for photo
            'photo.image' => 'ပုံဖိုင် (Image) သာ တင်ရန် လိုအပ်ပါသည်။',
            'photo.mimes' => 'jpeg, png, jpg, webp ပုံစံဖိုင်များသာ တင်ခွင့်ရှိသည်။',
            'photo.max' => 'ပုံအရွယ်အစားသည် 2MB ထက် မကြီးရပါ။',

            'items.required' => 'ကျေးဇူးပြု၍ အနည်းဆုံး ပစ္စည်းတစ်ခု ထည့်ပါ။',
            'items.min' => 'ကျေးဇူးပြု၍ အနည်းဆုံး ပစ္စည်းတစ်ခု ထည့်ပါ။',
            'items.*.item_id.required' => 'ပစ္စည်းကို ရွေးချယ်ရန် လိုအပ်ပါသည်။',
            'items.*.quantity.required' => 'ပစ္စည်းအရေအတွက် ထည့်ရန် လိုအပ်ပါသည်။',
            'items.*.quantity.min' => 'ပစ္စည်းအရေအတွက်သည် အနည်းဆုံး ၁ ရှိရပါမည်။',
        ];
    }
}
