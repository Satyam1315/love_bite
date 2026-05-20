<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            'Paneer' => [
                ['name' => 'Paneer Butter Masala', 'half' => 160, 'full' => 280, 'type' => 'veg'],
                ['name' => 'Kadai Paneer', 'half' => 170, 'full' => 300, 'type' => 'veg'],
                ['name' => 'Shahi Paneer', 'half' => 180, 'full' => 320, 'type' => 'veg'],
            ],
            'Biryani' => [
                ['name' => 'Veg Biryani', 'half' => 140, 'full' => 240, 'type' => 'veg'],
                ['name' => 'Chicken Biryani', 'half' => 190, 'full' => 340, 'type' => 'non-veg'],
                ['name' => 'Mutton Biryani', 'half' => 240, 'full' => 420, 'type' => 'non-veg'],
            ],
            'Chowmein' => [
                ['name' => 'Veg Chowmein', 'half' => 110, 'full' => 190, 'type' => 'veg'],
                ['name' => 'Chicken Chowmein', 'half' => 150, 'full' => 260, 'type' => 'non-veg'],
                ['name' => 'Egg Chowmein', 'half' => 130, 'full' => 220, 'type' => 'non-veg'],
            ],
            'Starters' => [
                ['name' => 'Paneer Tikka', 'half' => 190, 'full' => 340, 'type' => 'veg'],
                ['name' => 'Chicken 65', 'half' => 210, 'full' => 360, 'type' => 'non-veg'],
                ['name' => 'Crispy Corn', 'half' => 140, 'full' => 250, 'type' => 'veg'],
            ],
        ];

        foreach ($menu as $categoryName => $items) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($items as $item) {
                Food::updateOrCreate(
                    ['name' => $item['name']],
                    [
                        'category_id' => $category->id,
                        'price_half' => $item['half'],
                        'price_full' => $item['full'],
                        'image' => null,
                        'type' => $item['type'],
                        'stock' => 25,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
