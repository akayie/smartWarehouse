<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DonationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donation_id',
        'item_id',
        'quantity',
        'unit',
        'expired_date',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'expired_date' => 'date',
    ];

    /* ================= Relationships ================= */

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /* ================= Local Scopes ================= */

    /**
     * Warehouse Manager ၏ Warehouse များနှင့် သက်ဆိုင်သည့် Donation Items များကိုသာ Filter လုပ်ပေးရန် Scope
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
