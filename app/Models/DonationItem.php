<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donation_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * DonationItem belongs to Donation.
     */
    public function donation()
    {
        return $this->belongsTo(
            Donation::class
        );
    }

    /**
     * DonationItem belongs to Item.
     */
    public function item()
    {
        return $this->belongsTo(
            Item::class
        );
    }
}
