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

        // warehouse_id ကို တိုက်ရိုက် တိုက်စစ်ခြင်း
        return (int) $user->warehouse_id === (int) $inventory->warehouse_id;
    }
}
