<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToWarehouse;
use Illuminate\Support\Facades\Auth;

class StockMovement extends Model
{
    use HasFactory, BelongsToWarehouse;

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'type',
        'quantity',
        'balance_after',
        'expiry_date',
        'reference',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity'      => 'integer',
        'balance_after' => 'integer',
        'expiry_date'   => 'date',
    ];

    /* ================= Relationships ================= */

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ================= Query Scopes ================= */

    /**
     * Warehouse Manager သို့မဟုတ် Manager များအတွက် တာဝန်ကျရာ Warehouse အလိုက် စစ်ထုတ်ပေးသော Scope
     */
    public function scopeForCurrentWarehouse(Builder $query): Builder
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Admin သို့မဟုတ် Super Admin ဖြစ်လျှင် အားလုံးကို ပြမည်
            if (in_array($user->role, ['admin', 'super_admin'])) {
                return $query;
            }

            // Warehouse Manager သို့မဟုတ် Manager များအတွက် ၎င်းတို့ တာဝန်ကျရာ Warehouse များအလိုက် စစ်ထုတ်ခြင်း
            if (in_array($user->role, ['warehouse_manager', 'manager'])) {
                if (method_exists($user, 'warehouses')) {
                    $warehouseIds = $user->warehouses()->pluck('warehouses.id');
                    if ($warehouseIds->isNotEmpty()) {
                        return $query->whereIn('warehouse_id', $warehouseIds);
                    }
                }

                if (!empty($user->warehouse_id)) {
                    return $query->where('warehouse_id', $user->warehouse_id);
                }
            }
        }

        return $query;
    }
}
