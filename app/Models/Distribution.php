<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribution extends Model
{
    use HasFactory, SoftDeletes;

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
     * Distribution belongs to a relief request.
     */
    public function request()
    {
        return $this->belongsTo(
            ReliefRequest::class,
            'request_id'
        );
    }

    /**
     * Distribution belongs to a warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(
            Warehouse::class
        );
    }

    /**
     * Distribution is handled by a user.
     */
    public function handledBy()
    {
        return $this->belongsTo(
            User::class,
            'handled_by'
        );
    }

    public function distributionItems()
{
    return $this->hasMany(
        DistributionItem::class,
        'distribution_id'
    );
}
}
