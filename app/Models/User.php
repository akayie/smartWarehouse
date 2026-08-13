<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Warehouse;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'profile',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User has many warehouses.
     */
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'user_id');
    }
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'created_by');
    }

    public function reliefRequests()
{
    return $this->hasMany(
        ReliefRequest::class,
        'requested_by'
    );
}
public function distributions()
{
    return $this->hasMany(
        Distribution::class,
        'handled_by'
    );
}
}
