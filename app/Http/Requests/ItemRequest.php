<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route Update / Create အပေါ်မူတည်ပြီး Barcode Unique စစ်ရန်
        $itemId = $this->route('item') ? $this->route('item')->id : null;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unit'        => ['required', 'string', 'max:50'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'barcode'     => ['nullable', 'string', 'max:255', 'unique:items,barcode,' . $itemId],
            'expiry_date' => 'nullable|date|after_or_equal:today', // Expiry date validation // Optional: Create ချိန်တွင် null ဖြစ်နိုင်သည်
            'status'      => ['required', 'in:Active,Inactive'],
        ];
    }
}
