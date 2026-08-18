<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class WarehouseScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // User သည် Warehouse Manager ဖြစ်ပါက
            if ($user->role === 'warehouse_manager') {
                // User ပိုင်ဆိုင်သော Warehouse ID များကို ဆွဲထုတ်မည်
                $warehouseIds = $user->warehouses()->pluck('id');

                // Model ၏ Table name ဖြင့် warehouse_id ကို Filter လုပ်မည်
                $builder->whereIn($model->getTable() . '.warehouse_id', $warehouseIds);
            }
        }
    }
}
