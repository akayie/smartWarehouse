<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'donor_id',
        'warehouse_id',
        'donation_type',
        'donation_date',
        'status',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'donation_date' => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONSHIPS                               */
    /* -------------------------------------------------------------------------- */

    /**
     * Get the donor associated with the donation.
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get the target warehouse for the donation.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get all donated items for this donation.
     */
    public function donationItems(): HasMany
    {
        return $this->hasMany(DonationItem::class, 'donation_id');
    }

    /**
     * Get the payment transaction record associated with this donation.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(DonationPayment::class, 'donation_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                                QUERY SCOPES                                */
    /* -------------------------------------------------------------------------- */

    /**
     * Scope a query to only include pending donations.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to only include received donations.
     */
    public function scopeReceived(Builder $query): Builder
    {
        return $query->where('status', 'Received');
    }

    /* -------------------------------------------------------------------------- */
    /*                               HELPER METHODS                               */
    /* -------------------------------------------------------------------------- */

    /**
     * Check if the donation is still pending verification.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if the donation status is marked as received.
     */
    public function isReceived(): bool
    {
        return $this->status === 'Received';
    }
}
