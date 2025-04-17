<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Karthick',
            'mobile_no' => '9234567890',
            'email'=>'user1@gmail.com',
            'gender' => 'male',
            'company_name' => 'abcd',
            'designation' => 'service',
            'password'=>Hash::make('Admin@123')
        ]);
    }
}
