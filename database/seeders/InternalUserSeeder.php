<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\InternalUser;


class InternalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InternalUser::create([
            'role_id' => 1,
            'name'=>'Karthick',
            'mobile_no' => '9999999999',
            'email_id'=>'karthick@signify.com',
            'password'=>Hash::make('Admin@123')
        ]);
        InternalUser::create([
            'role_id' => 1,
            'name'=>'Asar',
            'mobile_no' => '9999999998',
            'email_id'=>'asar@signify.com',
            'password'=>Hash::make('Admin@123')
        ]);
    }
}
