<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DonationPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donation_id',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'account_name',
        'account_number',
        'amount',
        'currency',
        'proof',
        'status',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    /* ================= Relationships ================= */

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /* ================= Local Scopes ================= */

    /**
     * Warehouse Manager ၏ Warehouse များနှင့် သက်ဆိုင်သည့် Donation Payments များကိုသာ Filter လုပ်ပေးရန် Scope
     */
    public function scopeForCurrentWarehouse(Builder $query): Builder
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role === 'warehouse_manager') {
                $warehouseIds = $user->warehouses()->pluck('warehouses.id');

                return $query->whereHas('donation', function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                });
            }
        }

        return $query;
    }
}
