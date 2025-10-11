<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Board;

class BoardSeeder extends Seeder
{
    public function run(): void
    {
        Board::insert([
            [
                'name' => 'Mathematics',
                'thumbnail' => 'https://example.com/math.jpg',
                'description' => 'Algebra, Geometry, Calculus',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Science',
                'thumbnail' => 'https://example.com/science.jpg',
                'description' => 'Physics, Chemistry, Biology',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
