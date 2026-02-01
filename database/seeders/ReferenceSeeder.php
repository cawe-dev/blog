<?php

namespace Database\Seeders;

use App\Models\Reference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reference::factory()->count(3)->create();
    }
}
