<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Disaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'location',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /* ================= RELATIONSHIPS ================= */

    /**
     * Disaster has many relief requests.
     */
    public function reliefRequests(): HasMany
    {
        return $this->hasMany(ReliefRequest::class);
    }

    /**
     * Disaster မှတစ်ဆင့် ခွဲဝေလှူဒါန်းထားသော Distribution များကို တိုက်ရိုက် ရယူရန်
     */
    public function distributions(): HasManyThrough
    {
        return $this->hasManyThrough(Distribution::class, ReliefRequest::class, 'disaster_id', 'request_id');
    }

    /* ================= QUERY SCOPES ================= */

    /**
     * Active ဖြစ်နေဆဲ ဘေးအန္တရာယ်များကိုသာ Filter ရန် Scope
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'Active');
    }

    /**
     * Warehouse Manager ၏ Warehouse များနှင့် သက်ဆိုင်သည့် Relief Requests ရှိသော Disasters များကိုသာ Filter ပြုလုပ်ရန် Scope
     */
    public function scopeForCurrentWarehouse(Builder $query): Builder
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role === 'warehouse_manager') {
                $warehouseIds = $user->warehouses()->pluck('warehouses.id');

                return $query->whereHas('reliefRequests', function ($q) use ($warehouseIds) {
                    $q->whereIn('warehouse_id', $warehouseIds);
                });
            }
        }

        return $query;
    }
}
