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
     * Warehouse has many users/managers/staff.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'warehouse_id');
    }

    /**
     * Warehouse has many inventory records.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function reliefRequests()
    {
        return $this->hasMany(ReliefRequest::class);
    }
}
