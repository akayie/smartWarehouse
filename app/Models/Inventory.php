<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToWarehouse;

class Inventory extends Model
{
    use HasFactory, SoftDeletes, BelongsToWarehouse;

    protected $fillable = [
        'warehouse_id',
        'item_id',
        'quantity',
        'reserved_quantity',
        'expiry_date',
    ];

    protected $casts = [
    'quantity' => 'integer',
    'expiry_date' => 'date',
];
    /**
     * Get the warehouse that owns the inventory.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the item that owns the inventory.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
