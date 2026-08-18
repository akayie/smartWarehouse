<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToWarehouse;
class ReliefRequest extends Model
{
    use HasFactory, SoftDeletes,BelongsToWarehouse;

    protected $fillable = [
        'disaster_id',
        'warehouse_id',
        'requested_by',
        'location',
        'latitude',
        'longitude',
        'request_date',
        'status',
        'note',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class, 'request_id');
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'request_id');
    }
}
