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
            ['id' => 1, 'size_code' => '2.5X3', 'width' => 2.5, 'length' => 3, 'height' => 0.5],
            ['id' => 2, 'size_code' => '2.5X4', 'width' => 2.5, 'length' => 4, 'height' => 0.5],
            ['id' => 3, 'size_code' => '2.5X6', 'width' => 2.5, 'length' => 6, 'height' => 0.5],
            ['id' => 4, 'size_code' => '3X3', 'width' => 3, 'length' => 3, 'height' => 0.5],
            ['id' => 5, 'size_code' => '3X4', 'width' => 3, 'length' => 4, 'height' => 0.5],
            ['id' => 6, 'size_code' => '3X5', 'width' => 3, 'length' => 5, 'height' => 0.5],
            ['id' => 7, 'size_code' => '3X6', 'width' => 3, 'length' => 6, 'height' => 0.5],
            ['id' => 8, 'size_code' => '3X8', 'width' => 3, 'length' => 8, 'height' => 0.5],
            ['id' => 9, 'size_code' => '3X10', 'width' => 3, 'length' => 10, 'height' => 0.5],
            ['id' => 10, 'size_code' => '3X12', 'width' => 3, 'length' => 12, 'height' => 0.5],
            ['id' => 11, 'size_code' => '3.5X4', 'width' => 3.5, 'length' => 4, 'height' => 0.5],
            ['id' => 12, 'size_code' => '3.5X5', 'width' => 3.5, 'length' => 5, 'height' => 0.5],
            ['id' => 13, 'size_code' => '3.5X6', 'width' => 3.5, 'length' => 6, 'height' => 0.5],
            ['id' => 14, 'size_code' => '3.5X8', 'width' => 3.5, 'length' => 8, 'height' => 0.5],
            ['id' => 15, 'size_code' => '3.5X10', 'width' => 3.5, 'length' => 10, 'height' => 0.5],
            ['id' => 16, 'size_code' => '3.5X12', 'width' => 3.5, 'length' => 12, 'height' => 0.5],
            ['id' => 17, 'size_code' => '4X4', 'width' => 4, 'length' => 4, 'height' => 0.5],
            ['id' => 18, 'size_code' => '4X5', 'width' => 4, 'length' => 5, 'height' => 0.5],
            ['id' => 19, 'size_code' => '4X6', 'width' => 4, 'length' => 6, 'height' => 0.5],
            ['id' => 20, 'size_code' => '4X8', 'width' => 4, 'length' => 8, 'height' => 0.5],
            ['id' => 21, 'size_code' => '4X10', 'width' => 4, 'length' => 10, 'height' => 0.5],
            ['id' => 22, 'size_code' => '4X12', 'width' => 4, 'length' => 12, 'height' => 0.5],
            ['id' => 23, 'size_code' => '4.5X6', 'width' => 4.5, 'length' => 6, 'height' => 0.5],
            ['id' => 24, 'size_code' => '4.5X8', 'width' => 4.5, 'length' => 8, 'height' => 0.5],
            ['id' => 25, 'size_code' => '4.5X10', 'width' => 4.5, 'length' => 10, 'height' => 0.5],
            ['id' => 26, 'size_code' => '4.5X12', 'width' => 4.5, 'length' => 12, 'height' => 0.5],
            ['id' => 27, 'size_code' => '5X6', 'width' => 5, 'length' => 6, 'height' => 0.5],
            ['id' => 28, 'size_code' => '5X8', 'width' => 5, 'length' => 8, 'height' => 0.5],
            ['id' => 29, 'size_code' => '5X10', 'width' => 5, 'length' => 10, 'height' => 0.5],
            ['id' => 30, 'size_code' => '5X12', 'width' => 5, 'length' => 12, 'height' => 0.5],
            ['id' => 31, 'size_code' => '6X6', 'width' => 6, 'length' => 6, 'height' => 0.5],
            ['id' => 32, 'size_code' => '6X8', 'width' => 6, 'length' => 8, 'height' => 0.5],
            ['id' => 33, 'size_code' => '6X10', 'width' => 6, 'length' => 10, 'height' => 0.5],
            ['id' => 34, 'size_code' => '6X12', 'width' => 6, 'length' => 12, 'height' => 0.5],
            // Cushion sizes
            ['id' => 35, 'size_code' => '22X22', 'width' => 22, 'length' => 22, 'height' => 0.3, 'description' => 'Cushion'],
            ['id' => 36, 'size_code' => '24X24', 'width' => 24, 'length' => 24, 'height' => 0.3, 'description' => 'Cushion'],
            ['id' => 37, 'size_code' => '24X28', 'width' => 24, 'length' => 28, 'height' => 0.3, 'description' => 'Cushion'],
        ];

        foreach ($sizes as $size) {
            MattressSize::updateOrCreate(
                ['id' => $size['id']],
                $size
            );
        }
    }
}
