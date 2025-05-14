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
            ['id' => 1, 'name' => 'Standard', 'code' => 'STD', 'description' => 'Cover mattress type'],
            ['id' => 2, 'name' => 'Tape Edge', 'code' => 'TX', 'description' => 'Tape Edge mattress type'],
            ['id' => 3, 'name' => 'Qualified', 'code' => 'PQ', 'description' => 'Qualified mattress type'],
            ['id' => 4, 'name' => 'Plain', 'code' => 'PL', 'description' => 'Plain mattress type'],
            ['id' => 5, 'name' => 'Orthopedic', 'code' => 'Spring', 'description' => 'Orthopedic mattress type'],
        ];

        foreach ($types as $type) {
            MattressType::updateOrCreate(
                ['id' => $type['id']],
                $type
            );
        }
    }
}
