<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $role = Role::create(['name' => 'Admin']);
        // $pengguna = Role::create(['name' => 'Customer']);
        $bendahara = Role::create(['name' => 'Bendahara']);



        // $user = User::factory()->create([
        //     'name' => 'admin',
        //     'username' => 'admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('12345678'),
        // ]);

        // $user->assignRole($role);

        $user2 = User::factory()->create([
            'name' => 'bendahara',
            'username' => 'bendahara',
            'email' => 'bendahara@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $user2->assignRole('Bendahara');


    }
}
