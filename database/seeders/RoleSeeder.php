<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Admin', 'Superuser', 'Accounts', 'PR', 'HR', 'R&D'];

        foreach ($roles as $role) {
            Roles::create(['role_name' => $role]);
        }
    }
}
