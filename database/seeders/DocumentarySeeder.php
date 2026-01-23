<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Documentary;

class DocumentarySeeder extends Seeder
{
    public function run(): void
    {
        Documentary::create([
            'title' => 'The Future of AI',
            'description' => 'A deep dive into how artificial intelligence is shaping our world and what to expect in the next decade.',
            'content' => '<p>Artificial Intelligence is no longer just a buzzword. It is a transformative technology that is reshaping industries, from healthcare to finance. In this documentary, we explore the ethical implications, the technological breakthroughs, and the potential risks of superintelligent systems.</p>',
            'status' => 'published',
            'published_at' => now(),
            'video_url' => 'https://www.youtube.com/watch?v=5dgXreR772w',
            'views' => 1250
        ]);

        Documentary::create([
            'title' => 'Climate Change: The Turning Point',
            'description' => 'Experts discuss the critical state of our planet and the immediate actions required to prevent catastrophic warming.',
            'content' => '<p>The Earth is warming at an unprecedented rate. This documentary features 12 scientists who explain why the next five years are critical for our survival.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'video_url' => 'https://www.youtube.com/watch?v=0Puvu0Pss48',
            'views' => 2840
        ]);

        Documentary::create([
            'title' => 'Exploration of Mars',
            'description' => 'The history of Mars exploration and the upcoming manned missions to the Red Planet.',
            'content' => '<p>From the first orbiters to the latest rovers, Mars has always fascinated humanity. We are now closer than ever to setting foot on another planet.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(10),
            'video_url' => 'https://www.youtube.com/watch?v=tcuzS2h9IH0',
            'views' => 5600
        ]);
    }
}
