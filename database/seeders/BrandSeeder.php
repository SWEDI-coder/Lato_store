<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'QUALITY FOAM LTD (QFL)', 'description' => 'QFL-DODOMA'],
            ['name' => 'COMFY GOLD', 'description' => 'KEKO MODERN FURNITER LTD'],
            ['name' => 'COMFY', 'description' => 'KEKO MODERN FURNITURE & HARDWARE'],
            ['name' => 'KILI FOAM', 'description' => 'KEKO MODERN FURNITURE & HARDWARE'],
            ['name' => 'PAN FOAM', 'description' => 'KEKO MODERN FURNITURE'],
            ['name' => 'VITA FOAM', 'description' => 'VITA FOAM (T) LTD'],
            ['name' => 'VITA RAHA', 'description' => 'TBS APPROVED ORDINARY GRADE'],
            ['name' => 'VITA SUPREME', 'description' => 'TBS APPROVED SUPERIOR GRADE'],
            ['name' => 'MBEYA', 'description' => 'STANDARD (LD)'],
            ['name' => 'GOLD STAR', 'description' => 'DODOMA COMFORT LTD'],
            ['name' => 'JUMBO FOAM', 'description' => 'DODOMA COMFORT LTD'],
            ['name' => 'GOLDSUN', 'description' => 'Goldsun Mattress Ltd'],
            ['name' => 'FURAHA', 'description' => 'Furaha Mattress Ltd'],
            ['name' => 'DODOMA ASILI', 'description' => 'Dodoma Mattress Ltd'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
