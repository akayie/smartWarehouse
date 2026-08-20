<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DistributionItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'distribution_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /* ================= RELATIONSHIPS ================= */

    /**
     * Distribution item belongs to a distribution.
     */
    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    /**
     * Distribution item belongs to an item.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /* ================= QUERY SCOPES ================= */

    /**
     * Parent Distribution Model မှတစ်ဆင့် Warehouse Manager ၏ Data များကိုသာ Filter လုပ်ပေးရန် Scope
     */
    public function scopeForCurrentWarehouse(Builder $query): Builder
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role === 'warehouse_manager') {
                $warehouseIds = $user->warehouses()->pluck('warehouses.id');

                return $query->whereHas('distribution', function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                });
            }
        }

        return $query;
    }
}
