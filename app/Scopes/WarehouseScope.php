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
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // User သည် Warehouse Manager ဖြစ်ပါက
            if ($user->role === 'warehouse_manager') {

                // warehouses.id ဟု Table name အတိအကျ ဖော်ပြပေးရန်
                $warehouseIds = $user->warehouses()->pluck('warehouses.id');

                // Warehouse ID မရှိပါက Null query ဖြင့် Data အကုန်ကာကွယ်မည်
                if ($warehouseIds->isEmpty()) {
                    $builder->whereNull($model->getTable() . '.warehouse_id');
                } else {
                    $builder->whereIn($model->getTable() . '.warehouse_id', $warehouseIds);
                }
            }
        }
    }
}
