<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Court;
use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@badminton.com',
            'phone' => '0123456789',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '0123456780',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '0123456781',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Courts
        $courts = [
            [
                'court_name' => 'Court A',
                'court_type' => 'standard',
                'location' => 'Ground Floor - East Wing',
                'status' => 'available',
                'description' => 'Standard badminton court with premium flooring.',
                'hourly_rate' => 25.00,
            ],
            [
                'court_name' => 'Court B',
                'court_type' => 'standard',
                'location' => 'Ground Floor - East Wing',
                'status' => 'available',
                'description' => 'Standard badminton court with premium flooring.',
                'hourly_rate' => 25.00,
            ],
            [
                'court_name' => 'Court C',
                'court_type' => 'premium',
                'location' => 'Ground Floor - West Wing',
                'status' => 'available',
                'description' => 'Premium court with professional lighting and wood flooring.',
                'hourly_rate' => 35.00,
            ],
            [
                'court_name' => 'Court D',
                'court_type' => 'premium',
                'location' => 'Ground Floor - West Wing',
                'status' => 'available',
                'description' => 'Premium court with professional lighting and wood flooring.',
                'hourly_rate' => 35.00,
            ],
            [
                'court_name' => 'Court E',
                'court_type' => 'vip',
                'location' => 'First Floor - VIP Section',
                'status' => 'available',
                'description' => 'VIP court with air conditioning, private seating area, and refreshments included.',
                'hourly_rate' => 50.00,
            ],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }

        // Create a sample booking
        Booking::create([
            'user_id' => 2, // John Doe
            'court_id' => 1, // Court A
            'booking_date' => now()->addDays(1)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'approved',
            'notes' => 'Doubles match with friends',
        ]);

        Booking::create([
            'user_id' => 3, // Jane Smith
            'court_id' => 3, // Court C
            'booking_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'pending',
            'notes' => 'Practice session',
        ]);
    }
}
