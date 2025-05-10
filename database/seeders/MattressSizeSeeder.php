<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MattressSize;

class MattressSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['size_code' => '2.5X3', 'width' => 2.5, 'length' => 3, 'height' => 0.5],
            ['size_code' => '2.5X4', 'width' => 2.5, 'length' => 4, 'height' => 0.5],
            ['size_code' => '2.5X6', 'width' => 2.5, 'length' => 6, 'height' => 0.5],
            ['size_code' => '3X3', 'width' => 3, 'length' => 3, 'height' => 0.5],
            ['size_code' => '3X4', 'width' => 3, 'length' => 4, 'height' => 0.5],
            ['size_code' => '3X5', 'width' => 3, 'length' => 5, 'height' => 0.5],
            ['size_code' => '3X6', 'width' => 3, 'length' => 6, 'height' => 0.5],
            ['size_code' => '3X8', 'width' => 3, 'length' => 8, 'height' => 0.5],
            ['size_code' => '3X10', 'width' => 3, 'length' => 10, 'height' => 0.5],
            ['size_code' => '3X12', 'width' => 3, 'length' => 12, 'height' => 0.5],
            ['size_code' => '3.5X4', 'width' => 3.5, 'length' => 4, 'height' => 0.5],
            ['size_code' => '3.5X5', 'width' => 3.5, 'length' => 5, 'height' => 0.5],
            ['size_code' => '3.5X6', 'width' => 3.5, 'length' => 6, 'height' => 0.5],
            ['size_code' => '3.5X8', 'width' => 3.5, 'length' => 8, 'height' => 0.5],
            ['size_code' => '3.5X10', 'width' => 3.5, 'length' => 10, 'height' => 0.5],
            ['size_code' => '3.5X12', 'width' => 3.5, 'length' => 12, 'height' => 0.5],
            ['size_code' => '4X4', 'width' => 4, 'length' => 4, 'height' => 0.5],
            ['size_code' => '4X5', 'width' => 4, 'length' => 5, 'height' => 0.5],
            ['size_code' => '4X6', 'width' => 4, 'length' => 6, 'height' => 0.5],
            ['size_code' => '4X8', 'width' => 4, 'length' => 8, 'height' => 0.5],
            ['size_code' => '4X10', 'width' => 4, 'length' => 10, 'height' => 0.5],
            ['size_code' => '4X12', 'width' => 4, 'length' => 12, 'height' => 0.5],
            ['size_code' => '4.5X6', 'width' => 4.5, 'length' => 6, 'height' => 0.5],
            ['size_code' => '4.5X8', 'width' => 4.5, 'length' => 8, 'height' => 0.5],
            ['size_code' => '4.5X10', 'width' => 4.5, 'length' => 10, 'height' => 0.5],
            ['size_code' => '4.5X12', 'width' => 4.5, 'length' => 12, 'height' => 0.5],
            ['size_code' => '5X6', 'width' => 5, 'length' => 6, 'height' => 0.5],
            ['size_code' => '5X8', 'width' => 5, 'length' => 8, 'height' => 0.5],
            ['size_code' => '5X10', 'width' => 5, 'length' => 10, 'height' => 0.5],
            ['size_code' => '5X12', 'width' => 5, 'length' => 12, 'height' => 0.5],
            ['size_code' => '6X6', 'width' => 6, 'length' => 6, 'height' => 0.5],
            ['size_code' => '6X8', 'width' => 6, 'length' => 8, 'height' => 0.5],
            ['size_code' => '6X10', 'width' => 6, 'length' => 10, 'height' => 0.5],
            ['size_code' => '6X12', 'width' => 6, 'length' => 12, 'height' => 0.5],
            // Cushion sizes
            ['size_code' => '22X22', 'width' => 22, 'length' => 22, 'height' => 0.3, 'description' => 'Cushion'],
            ['size_code' => '24X24', 'width' => 24, 'length' => 24, 'height' => 0.3, 'description' => 'Cushion'],
            ['size_code' => '24X28', 'width' => 24, 'length' => 28, 'height' => 0.3, 'description' => 'Cushion'],
        ];

        foreach ($sizes as $size) {
            MattressSize::create($size);
        }
    }
}
