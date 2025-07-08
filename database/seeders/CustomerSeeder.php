<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'customer1@example.com',
                'password' => Hash::make('password'),
                'phone' => '0412345678',
                'street_number' => '123',
                'street_name' => 'Collins',
                'street_type' => 'Street',
                'suburb' => 'Melbourne',
                'state' => 'VIC',
                'postcode' => '3000',
                'is_active' => true,
                'date_of_birth' => '1985-06-15',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Thompson',
                'email' => 'customer2@example.com',
                'password' => Hash::make('password'),
                'phone' => '0423456789',
                'street_number' => '456',
                'street_name' => 'George',
                'street_type' => 'Street',
                'unit_number' => '12',
                'suburb' => 'Sydney',
                'state' => 'NSW',
                'postcode' => '2000',
                'is_active' => true,
                'date_of_birth' => '1990-03-22',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Davis',
                'email' => 'customer3@example.com',
                'password' => Hash::make('password'),
                'phone' => '0434567890',
                'street_number' => '789',
                'street_name' => 'Queen',
                'street_type' => 'Street',
                'suburb' => 'Brisbane',
                'state' => 'QLD',
                'postcode' => '4000',
                'is_active' => true,
                'date_of_birth' => '1982-11-08',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'customer4@example.com',
                'password' => Hash::make('password'),
                'phone' => '0445678901',
                'street_number' => '321',
                'street_name' => 'Murray',
                'street_type' => 'Street',
                'suburb' => 'Perth',
                'state' => 'WA',
                'postcode' => '6000',
                'is_active' => true,
                'date_of_birth' => '1987-09-12',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'customer5@example.com',
                'password' => Hash::make('password'),
                'phone' => '0456789012',
                'street_number' => '654',
                'street_name' => 'Rundle',
                'street_type' => 'Street',
                'suburb' => 'Adelaide',
                'state' => 'SA',
                'postcode' => '5000',
                'is_active' => true,
                'date_of_birth' => '1992-12-03',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
