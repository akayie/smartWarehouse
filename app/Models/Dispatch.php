<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;

    protected $table = 'dispatches';

    protected $fillable = [
        'relief_request_id',
        'warehouse_id',
        'status', // e.g., 'Pending', 'In Transit', 'Delivered'
        'dispatched_at',
    ];
}
