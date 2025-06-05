<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'password' => Hash::make('password'),
                'phone' => '0123456789',
            ]
        );
    }
}
