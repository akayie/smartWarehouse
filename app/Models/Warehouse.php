<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Inventory;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'phone',
        'user_id',
        'status',
    ];

    /**
     * Warehouse belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
    return $this->hasMany(
        Distribution::class
    );
}
}
