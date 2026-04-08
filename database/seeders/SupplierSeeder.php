<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::insert([
            [
                'name' => 'Quincaillerie Centrale', 
                'contact' => '00229 90000001', 
                'phone' => '00229 90000001',  // Remplace 'email' par 'phone'
                'address' => 'Cotonou'
            ],
            [
                'name' => 'Fournitures Électriques Bénin', 
                'contact' => '00229 90000002', 
                'phone' => '00229 90000002',  // Remplace 'email' par 'phone'
                'address' => 'Porto-Novo'
            ],
            [
                'name' => 'BTP Matériaux SARL', 
                'contact' => '00229 90000003', 
                'phone' => '00229 90000003',  // Remplace 'email' par 'phone'
                'address' => 'Parakou'
            ],
        ]);
    }
}