<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'unit',
        'minimum_stock',
        'barcode',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
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

    // Calculate total current stock across inventories
    public function getTotalStockAttribute(): int
    {
        return (int) $this->inventories()->sum('quantity');
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
