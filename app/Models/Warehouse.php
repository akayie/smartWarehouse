<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'phone',
        'status',
    ];

    /**
     * Get all users/staff assigned to this warehouse.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'warehouse_id');
    }

    /**
     * Alias for single user / primary contact relationship
     * Fixes: RelationNotFoundException [user] on model Warehouse
     */
    public function user()
    {
        return $this->hasOne(User::class, 'warehouse_id');
    }

    /**
     * Warehouse has many inventory records.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Warehouse has many stock movements.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Warehouse receives many donations.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Warehouse handles many distributions.
     */
    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    /**
     * Warehouse handles many relief requests.
     */
    public function reliefRequests()
    {
        return $this->hasMany(ReliefRequest::class);
    }
}
