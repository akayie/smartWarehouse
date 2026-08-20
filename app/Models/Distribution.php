<?php

namespace App\Models;

use App\Traits\BelongsToWarehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribution extends Model
{
    use HasFactory, SoftDeletes, BelongsToWarehouse;

    protected $fillable = [
        'request_id',
        'warehouse_id',
        'handled_by',
        'distribution_date',
        'status',
        'funding_amount',
        'note',
    ];

    protected $casts = [
        'distribution_date' => 'date',
        'funding_amount' => 'decimal:2',
    ];

    /* =====================================================
       RELATIONSHIPS
    ====================================================== */

    /**
     * Distribution belongs to Relief Request
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(
            ReliefRequest::class,
            'request_id'
        );
    }

    /**
     * Backward compatibility
     */
    public function reliefRequest(): BelongsTo
    {
        return $this->request();
    }

    /**
     * Distribution belongs to Warehouse
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(
            Warehouse::class,
            'warehouse_id'
        );
    }

    /**
     * Distribution handled by User
     */
    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'handled_by'
        );
    }

    /**
     * Distribution has many Distribution Items
     */
    public function distributionItems(): HasMany
    {
        return $this->hasMany(
            DistributionItem::class,
            'distribution_id'
        );
    }

    /**
     * Alias: items
     *
     * Controller / Blade မှာ
     * $distribution->items
     * သုံးချင်တဲ့အတွက်
     */
    public function items(): HasMany
    {
        return $this->distributionItems();
    }
}
