<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToWarehouse;
class StockMovement extends Model
{
    use HasFactory,BelongsToWarehouse;

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'type',
        'quantity',
        'balance_after',
        'expiry_date', // <--- Expiry Date ကို fillable ထဲ ထည့်သွင်းထားပါသည်
        'reference',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity'      => 'integer',
        'balance_after' => 'integer',
        'expiry_date'   => 'date', // <--- Carbon Date Object အဖြစ် Auto Cast လုပ်ပေးပါမည်
    ];

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
}
