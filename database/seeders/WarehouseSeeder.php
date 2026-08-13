<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::updateOrCreate(
            ['id' => 1],
            [
                'name'     => 'Main Warehouse',
                'location' => 'Default Location',
                'phone'    => '09123456789', // phone field ထည့်သွင်းပေးထားပါသည်
                'status'   => 'Active',
            ]
        );
    }
}
