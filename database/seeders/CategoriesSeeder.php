<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        Category::create([
            'name'          => 'Men',
            'description'   => 'The parent category for all products meant for men.'
        ]);

        Category::create([
            'name'          => 'Women',
            'description'   => 'The parent category for all products meant for women.'
        ]);

        // Sup categories for men
        Category::create([
            'name'          => 'Men Shoes',
            'description'   => 'The category for all shoes meant for men.',
            'parent_id'     => 1
        ]);

        // Sup categories for women
        Category::create([
            'name'          => 'Women Shoes',
            'description'   => 'The category for all shoes meant for women.',
            'parent_id'     => 2
        ]);
    }
}
