<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Coffee'],
            ['name' => 'Tea'],
            ['name' => 'Pastries'],
            ['name' => 'Sandwiches'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        $products = [
            [
                'name' => 'Espresso',
                'price' => 3.50,
                'stock' => 50,
                'category_id' => 1,
            ],
            [
                'name' => 'Cappuccino',
                'price' => 4.00,
                'stock' => 40,
                'category_id' => 1,
            ],
            [
                'name' => 'Green Tea',
                'price' => 2.50,
                'stock' => 30,
                'category_id' => 2,
            ],
            [
                'name' => 'Croissant',
                'price' => 2.75,
                'stock' => 25,
                'category_id' => 3,
            ],
            [
                'name' => 'Club Sandwich',
                'price' => 8.50,
                'stock' => 15,
                'category_id' => 4,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
