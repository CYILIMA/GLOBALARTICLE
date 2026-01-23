<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get all unique category strings from News table
        $legacyCategories = News::select('category')->distinct()->pluck('category');

        foreach ($legacyCategories as $catName) {
            if (!$catName) continue;

            // 2. Create or First Category
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                [
                    'name' => ucfirst($catName),
                    'color' => $this->getRandomColor(),
                ]
            );

            // 3. Update all News with this string to have the correct ID
            News::where('category', $catName)->update(['category_id' => $category->id]);
        }
        
        // Also seed some default ones if they don't exist
        $defaults = ['Politics', 'Technology', 'Sports', 'Entertainment', 'Economy', 'Health', 'Documentary', 'Religion'];
        foreach ($defaults as $def) {
            Category::firstOrCreate(
                ['slug' => Str::slug($def)],
                [
                    'name' => $def,
                    'color' => $this->getRandomColor(),
                ]
            );
        }
    }

    private function getRandomColor()
    {
        $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1'];
        return $colors[array_rand($colors)];
    }
}
