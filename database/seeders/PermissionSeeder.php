<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Riwayat Permission
        Permission::create(['name' => 'add riwayat']);
        Permission::create(['name' => 'edit riwayat']);
        Permission::create(['name' => 'delete riwayat']);
        Permission::create(['name' => 'show riwayat']);

    }
}
