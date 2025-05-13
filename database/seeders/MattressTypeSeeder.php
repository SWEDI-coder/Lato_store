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
            ['name' => 'Standard', 'code' => 'STD', 'description' => 'Cover mattress type'],
            ['name' => 'Tape Edge', 'code' => 'TX', 'description' => 'Tape Edge mattress type'],
            ['name' => 'Qualified', 'code' => 'PQ', 'description' => 'Qualified mattress type'],
            ['name' => 'Plain', 'code' => 'PL', 'description' => 'Plain mattress type'],
            ['name' => 'Orthopedic', 'code' => 'Spring', 'description' => 'Orthopedic mattress type'],
        ];

        foreach ($types as $type) {
            MattressType::create($type);
        }
    }
}
