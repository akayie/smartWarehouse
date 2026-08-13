<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'request_id'        => 'nullable|exists:relief_requests,id',
            'warehouse_id'      => 'required|exists:warehouses,id',
            'handled_by'        => 'required|exists:users,id',
            'distribution_date' => 'required|date',
            'status'            => 'required|in:Pending,Processing,Completed,Cancelled',
            'note'              => 'nullable|string',

            // Dynamic Items Validation
            'items'             => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|integer|min:1',
            'items.*.expiry_date' => 'nullable|date',
        ];
    }
}
