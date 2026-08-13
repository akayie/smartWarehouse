<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'item_id',
        'quantity',
        'expiry_date', // 1. Mass assignment ပြုလုပ်နိုင်ရန် ထည့်သွင်းပေးရပါမည်
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expiry_date' => 'date', // 2. Carbon instance အဖြစ် တိုက်ရိုက် သုံးနိုင်ရန် date cast လုပ်ပေးထားပါသည်
    ];

    /**
     * Inventory belongs to a Warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Inventory belongs to an Item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
