<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'warehouse_id',
        'donation_type',
        'donation_date',
        'status',
        'note',
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    /**
     * Donation belongs to a donor.
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Donation belongs to a warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    /**
     * Donation has many donation items.
     */
    public function donationItems()
    {
        return $this->hasMany(
            DonationItem::class
        );
    }
    /**
     * Donation has many money donations.
     */
    public function donationPayment()
    {
        return $this->hasMany(
            DonationPayment::class
        );
    }
    // app/Models/Donation.php

public function payment()
{
    // ဒုတိယ parameter တွင် သင့် donation_payments table ထဲမှ Foreign key column အမည်ကို ထည့်ပေးပါ
    return $this->hasOne(DonationPayment::class, 'donation_id');
}

public function items()
{
    return $this->hasMany(DonationItem::class, 'donation_id');
}
}
