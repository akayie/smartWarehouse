<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToWarehouse;
class Distribution extends Model
{
    use HasFactory, SoftDeletes,BelongsToWarehouse;

    protected $fillable = [
        'request_id',
        'warehouse_id',
        'handled_by',
        'distribution_date',
        'status',
        'note',
    ];

    protected $casts = [
        'distribution_date' => 'date',
    ];

    /**
     * Distribution belongs to a Relief Request.
     */
    public function request()
    {
        return $this->belongsTo(
            ReliefRequest::class,
            'request_id'
        );
    }

    /**
     * Alias method for backward compatibility
     */
    public function reliefRequest()
    {
        return $this->request();
    }

    /**
     * Distribution belongs to a Warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(
            Warehouse::class,
            'warehouse_id'
        );
    }

    /**
     * Distribution is handled by a User.
     */
    public function handledBy()
    {
        return $this->belongsTo(
            User::class,
            'handled_by'
        );
    }

    /**
     * Distribution has many Distribution Items.
     */
    public function distributionItems()
    {
        return $this->hasMany(
            DistributionItem::class,
            'distribution_id'
        );
    }
}
