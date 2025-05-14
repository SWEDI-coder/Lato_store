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
            ['id' => 1, 'name' => 'QUALITY FOAM LTD', 'description' => 'QFL-DODOMA'],
            ['id' => 2, 'name' => 'COMFY GOLD', 'description' => 'KEKO MODERN FURNITER LTD'],
            ['id' => 3, 'name' => 'COMFY', 'description' => 'KEKO MODERN FURNITURE & HARDWARE'],
            ['id' => 4, 'name' => 'KILI FOAM', 'description' => 'KEKO MODERN FURNITURE & HARDWARE'],
            ['id' => 5, 'name' => 'PAN FOAM', 'description' => 'KEKO MODERN FURNITURE'],
            ['id' => 6, 'name' => 'VITA FOAM', 'description' => 'VITA FOAM (T) LTD'],
            ['id' => 7, 'name' => 'VITA RAHA', 'description' => 'TBS APPROVED ORDINARY GRADE'],
            ['id' => 8, 'name' => 'VITA SUPREME', 'description' => 'TBS APPROVED SUPERIOR GRADE'],
            ['id' => 9, 'name' => 'MBEYA', 'description' => 'STANDARD (LD)'],
            ['id' => 10, 'name' => 'GOLD STAR', 'description' => 'DODOMA COMFORT LTD'],
            ['id' => 11, 'name' => 'JUMBO FOAM', 'description' => 'DODOMA COMFORT LTD'],
            ['id' => 12, 'name' => 'GOLDSUN', 'description' => 'Goldsun Mattress Ltd'],
            ['id' => 13, 'name' => 'FURAHA', 'description' => 'Furaha Mattress Ltd'],
            ['id' => 14, 'name' => 'DODOMA ASILI', 'description' => 'Dodoma Mattress Ltd'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['id' => $brand['id']], // The unique identifier to find the record
                $brand // The data to update or create with
            );
        }
    }
}
