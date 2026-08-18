<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inventory;

class InventoryPolicy
{
    public function view(User $user, Inventory $inventory)
    {
        return $this->checkAccess($user, $inventory);
    }

    public function update(User $user, Inventory $inventory)
    {
        return $this->checkAccess($user, $inventory);
    }

    public function delete(User $user, Inventory $inventory)
    {
        return $this->checkAccess($user, $inventory);
    }

    private function checkAccess(User $user, Inventory $inventory)
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->warehouses()->where('warehouses.id', $inventory->warehouse_id)->exists();
    }
}
