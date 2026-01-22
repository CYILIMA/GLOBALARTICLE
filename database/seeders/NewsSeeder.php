<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $categories = ['politics', 'technology', 'sports', 'entertainment', 'economy', 'health', 'documentary', 'religion'];

        foreach ($categories as $category) {
            News::create([
                'title' => 'Breaking: Major Event in ' . ucfirst($category),
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'category' => $category,
                'image' => null, // Or a placeholder URL if you have one
                'breaking' => rand(0, 1),
                'created_at' => now()->subDays(rand(0, 10)),
            ]);
        }
        
        // Add one specific Breaking News
        News::create([
            'title' => 'GLOBAL ALERT: Market hits record highs today',
            'content' => 'Full report on the economic surge observed across global markets.',
            'category' => 'economy',
            'breaking' => true,
        ]);
    }
}
