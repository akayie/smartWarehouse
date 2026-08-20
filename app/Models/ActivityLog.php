<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'status',
        'ip_address',
    ];

    /**
     * Activity Log တစ်ခုကို ပြုလုပ်ခဲ့သော User ကို ရယူခြင်း
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
