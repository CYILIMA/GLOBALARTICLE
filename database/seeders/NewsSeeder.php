<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $categories = \App\Models\Category::all();
        
        foreach ($categories as $category) {
            for ($i = 0; $i < 3; $i++) {
                News::create([
                    'title' => 'Breaking: Major Event in ' . $category->name . ' (' . ($i+1) . ')',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    'category' => $category->name,
                    'category_id' => $category->id,
                    'image' => null,
                    'breaking' => ($i == 0),
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(0, 10)),
                    'created_at' => now()->subDays(rand(0, 10)),
                ]);
            }
        }
        
        // Add one specific Global Alert
        $economy = \App\Models\Category::where('slug', 'economy')->first();
        News::create([
            'title' => 'GLOBAL ALERT: Market hits record highs today',
            'content' => 'Full report on the economic surge observed across global markets.',
            'category' => $economy ? $economy->name : 'economy',
            'category_id' => $economy ? $economy->id : null,
            'breaking' => true,
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
