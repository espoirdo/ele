<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Music', 'slug' => 'music', 'icone' => 'fa-music', 'ordre' => 1],
            ['nom' => 'Concert', 'slug' => 'concert', 'icone' => 'fa-guitar', 'ordre' => 2],
            ['nom' => 'Formation', 'slug' => 'formation', 'icone' => 'fa-graduation-cap', 'ordre' => 3],
            ['nom' => 'Sport', 'slug' => 'sport', 'icone' => 'fa-futbol', 'ordre' => 4],
            ['nom' => 'Festival', 'slug' => 'festival', 'icone' => 'fa-star', 'ordre' => 5],
            ['nom' => 'Masterclass', 'slug' => 'masterclass', 'icone' => 'fa-chalkboard-teacher', 'ordre' => 6],
            ['nom' => 'Corporate', 'slug' => 'corporate', 'icone' => 'fa-briefcase', 'ordre' => 7],
            ['nom' => 'Culture', 'slug' => 'culture', 'icone' => 'fa-theater-masks', 'ordre' => 8],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}