<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donation_id',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'account_name',
        'account_number',
        'amount',
        'currency',
        'proof',
        'status',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
