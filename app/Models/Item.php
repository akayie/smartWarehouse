<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
   use HasFactory, SoftDeletes;

    protected $fillable = [
    'category_id',
    'name',
    'description',
    'price',
    'unit',
    'minimum_stock',
    'expiry_date',
    'barcode',
    'status',
];

    protected $casts = [
        'expiry_date' => 'date',
        'price'       => 'decimal:2', // 👈 ဈေးနှုန်းအတွက် Cast ထည့်ရန် (Optional)
    ];

    /* ================= Relationships ================= */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function donationItems()
    {
        return $this->hasMany(DonationItem::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class, 'item_id');
    }

    public function distributionItems()
    {
        return $this->hasMany(DistributionItem::class);
    }

    /* ================= Accessors & Helpers ================= */

    /**
     * Calculate stock based on logged-in user role.
     * If Warehouse Manager: returns total stock for THEIR warehouses only.
     * If Admin: returns total global stock across ALL warehouses.
     */
    public function getTotalStockAttribute(): int
    {
        // Inventory Model တွင် BelongsToWarehouse Trait ပါရှိပါက
        // Manager ဖြစ်လျှင် WarehouseScope မှ အလိုအလျောက် Filter လုပ်ပေးမည်။
        return (int) $this->inventories()->sum('quantity');
    }

    /**
     * Admin သီးသန့်ဖြစ်ပြီး Warehouse အားလုံး၏ Total Global Stock ကို ရယူချင်ပါက ခေါ်သုံးရန် Helper Method
     */
    public function getGlobalStockAttribute(): int
    {
        return (int) $this->inventories()
            ->withoutGlobalScope(\App\Scopes\WarehouseScope::class)
            ->sum('quantity');
    }

    // Check if stock is low
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->minimum_stock;
    }

    // Check if item is expired
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    // Check if item expires within 30 days
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date
            && !$this->is_expired
            && $this->expiry_date->diffInDays(now()) <= 30;
    }
}
