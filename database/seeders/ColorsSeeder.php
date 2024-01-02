<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::create([
            'name'      => 'Red',
            'value'     => '#ce592f'
        ]);

        Color::create([
            'name'      => 'Green',
            'value'     => '#81d742'
        ]);

        Color::create([
            'name'      => 'Blue',
            'value'     => '#1e73be'
        ]);

        Color::create([
            'name'      => 'Black',
            'value'     => '#121212'
        ]);
    }
}
