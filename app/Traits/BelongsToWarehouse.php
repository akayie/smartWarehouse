<?php

namespace App\Traits;

use App\Scopes\WarehouseScope;

trait BelongsToWarehouse
{
    /**
     * Boot the trait for a model.
     */
    protected static function bootedBelongsToWarehouse()
    {
        static::addGlobalScope(new WarehouseScope);
    }
}
