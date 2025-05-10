<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MattressType;

class MattressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Uncover', 'code' => 'UNCOVER', 'description' => 'Uncover mattress type'],
            ['name' => 'Cover', 'code' => 'COVER', 'description' => 'Cover mattress type'],
            ['name' => 'Tape Edge', 'code' => 'TX', 'description' => 'Tape Edge mattress type'],
            ['name' => 'Quilted', 'code' => 'QUILTED', 'description' => 'Quilted mattress type'],
            ['name' => 'PQM', 'code' => 'PQM', 'description' => 'PQM mattress type'],
            ['name' => 'Plain', 'code' => 'PL', 'description' => 'Plain mattress type'],
            ['name' => 'Luxe with covers', 'code' => 'LX', 'description' => 'Luxe with covers mattress type'],
            ['name' => 'Executive Tappage', 'code' => 'EXT', 'description' => 'Executive Tappage mattress type'],
            ['name' => 'Bila Kava', 'code' => 'WOC', 'description' => 'Bila Kava mattress type'],
            ['name' => 'Na Kava', 'code' => 'WC', 'description' => 'Na Kava mattress type'],
            ['name' => 'Orthopedic', 'code' => 'ORTHOPEDIC', 'description' => 'Orthopedic mattress type'],
        ];

        foreach ($types as $type) {
            MattressType::create($type);
        }
    }
}
