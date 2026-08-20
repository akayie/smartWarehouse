<?php

namespace App\Models;

use App\Traits\BelongsToWarehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReliefRequest extends Model
{
    use HasFactory, SoftDeletes, BelongsToWarehouse;

    protected $table = 'relief_requests';

    protected $fillable = [
        'disaster_id',
        'warehouse_id',
        'requested_by',

        // Requester Information
        'name',
        'phone_number',

        // Health / Medical Information
        'is_health_related',
        'medical_proof',

        // Location Information
        'location',
        'latitude',
        'longitude',

        // Request Information
        'request_date',
        'status',
        'note',
    ];

    protected $casts = [
        'is_health_related' => 'boolean',

        'request_date' => 'datetime',

        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User who submitted the relief request
     */
    public function requestedBy()
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    /**
     * Disaster related to this request
     */
    public function disaster()
    {
        return $this->belongsTo(
            Disaster::class,
            'disaster_id'
        );
    }

    /**
     * Warehouse handling this request
     */
    public function warehouse()
    {
        return $this->belongsTo(
            Warehouse::class,
            'warehouse_id'
        );
    }

    /**
     * Items requested
     */
    public function requestItems()
    {
        return $this->hasMany(
            RequestItem::class,
            'request_id'
        );
    }

    /**
     * Distributions created from this request
     */
    public function distributions()
    {
        return $this->hasMany(
            Distribution::class,
            'request_id'
        );
    }
}
