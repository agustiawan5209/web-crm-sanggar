<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\GetDiskon;
use App\Models\KeepDiskon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // GetDiskon::create(['min_quantity'=> '10']);
        // KeepDiskon::create(['min_frequency'=> '3']);
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
