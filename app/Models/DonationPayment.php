<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donation_id',            // donation_money_id အစား donation_id သို့ ပြောင်းပါ
        'payment_method',
        'transaction_reference',
        'payment_date',
        'account_name',
        'account_number',
        'amount',
        'currency',               // DonationMoney မှ currency ကို ဒီမှာ ထည့်ပါ
        'proof',
        'status',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    /**
     * DonationPayment belongs directly to Donation.
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
