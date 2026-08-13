<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistributionItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'distribution_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Distribution item belongs to a distribution.
     */
    public function distribution()
    {
        return $this->belongsTo(
            Distribution::class
        );
    }

    /**
     * Distribution item belongs to an item.
     */
    public function item()
    {
        return $this->belongsTo(
            Item::class
        );
    }
}
