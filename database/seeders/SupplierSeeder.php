<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'name' => 'ABC Hardware Supplies',
            'email' => 'sales@abchardware.com',
            'phone' => '9876543210',
            'contact_person' => 'Mr. Raj',
            'address' => '12 Market Road, Kolkata',
            'notes' => 'Preferred supplier for hand tools',
        ]);

        Supplier::create([
            'name' => 'Tools World Pvt Ltd',
            'email' => 'info@toolsworld.com',
            'phone' => '9123456780',
            'contact_person' => 'Ms. Priya',
            'address' => '32 Industrial Area, Pune',
            'notes' => 'Good credit terms',
        ]);

        Supplier::create([
            'name' => 'Prime Industrial Supplies',
            'email' => 'prime@industrial.com',
            'phone' => '9012345678',
            'contact_person' => 'Mr. Suresh',
            'address' => '45 Sector 7, Gurgaon',
            'notes' => 'Fast delivery',
        ]);
    }
}
