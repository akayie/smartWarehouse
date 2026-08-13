<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Request item belongs to a relief request.
     */
    public function request()
    {
        return $this->belongsTo(
            ReliefRequest::class,
            'request_id'
        );
    }

    /**
     * Request item belongs to an item.
     */
    public function item()
    {
        return $this->belongsTo(
            Item::class,
            'item_id'
        );
    }
}
