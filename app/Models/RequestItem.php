<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class RequestItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'request_items';

    protected $fillable = [
        'request_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'request_id' => 'integer',
        'item_id'    => 'integer',
        'quantity'   => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * RequestItem belongs to a ReliefRequest.
     */
    public function request()
    {
        return $this->belongsTo(
            ReliefRequest::class,
            'request_id'
        );
    }

    /**
     * RequestItem belongs to an Item.
     */
    public function item()
    {
        return $this->belongsTo(
            Item::class,
            'item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Get RequestItems that belong to the current warehouse.
     *
     * ReliefRequest contains warehouse_id,
     * so we filter through the request relationship.
     */
    public function scopeForCurrentWarehouse(
        Builder $query
    ): Builder {

        $user = auth()->user();

        // If no user is logged in, return no data.
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        // Admin can see all warehouse request items.
        if ($user->role === 'admin') {
            return $query;
        }

        // Warehouse Manager / Staff
        if (!empty($user->warehouse_id)) {

            return $query->whereHas(
                'request',
                function (Builder $requestQuery) use ($user) {

                    $requestQuery->where(
                        'warehouse_id',
                        $user->warehouse_id
                    );
                }
            );
        }

        // User has no warehouse assigned.
        return $query->whereRaw('1 = 0');
    }
}
