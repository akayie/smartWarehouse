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
        'unit',
        'expired_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expired_date' => 'date',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
