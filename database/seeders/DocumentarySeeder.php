<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Documentary;

class DocumentarySeeder extends Seeder
{
    public function run(): void
    {
        $adminId = \App\Models\User::where('role', 'admin')->first()?->id ?? 1;

        Documentary::create([
            'user_id' => $adminId,
            'title' => 'The Future of AI: Genesis to Singularity',
            'description' => 'A deep dive into how artificial intelligence is shaping our world and what to expect in the next decade.',
            'content' => '<p>Artificial Intelligence is no longer just a buzzword. It is a transformative technology that is reshaping industries, from healthcare to finance. In this documentary, we explore the ethical implications, the technological breakthroughs, and the potential risks of superintelligent systems.</p><p>We interview top researchers from Google DeepMind, OpenAI, and MIT to understand if we are heading towards a utopia or a technological winter.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(1),
            'video_url' => 'https://www.youtube.com/watch?v=5dgGZsh_CVA',
            'views' => 12503
        ]);

        Documentary::create([
            'user_id' => $adminId,
            'title' => 'Climate Crisis: The Final Warning',
            'description' => 'Experts discuss the critical state of our planet and the immediate actions required to prevent catastrophic warming.',
            'content' => '<p>The Earth is warming at an unprecedented rate. This documentary features 12 scientists who explain why the next five years are critical for our survival.</p><p>From the melting ice caps in the Arctic to the raging wildfires in Australia, we witness the devastating impact of climate change firsthand.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'video_url' => 'https://www.youtube.com/watch?v=G4H1N_yXBiA',
            'views' => 28401
        ]);

        Documentary::create([
            'user_id' => $adminId,
            'title' => 'Mars: The Next Frontier',
            'description' => 'The history of Mars exploration and the upcoming manned missions to the Red Planet.',
            'content' => '<p>From the first orbiters to the latest rovers, Mars has always fascinated humanity. We are now closer than ever to setting foot on another planet.</p><p>Explore the engineering marvels behind Starship and the architectural plans for the first human colony in Jezero Crater.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(10),
            'video_url' => 'https://www.youtube.com/watch?v=udFxXqT44mQ',
            'views' => 56002
        ]);

        Documentary::create([
            'user_id' => $adminId,
            'title' => 'Secrets of the Deep Ocean',
            'description' => 'Journey into the abyss to discover bizarre creatures and alien-like landscapes that remain unexplored.',
            'content' => '<p>The deep ocean remains one of the least explored places on Earth. Using advanced submersibles, we descend into the Mariana Trench to witness life in the most extreme conditions.</p>',
            'status' => 'published',
            'published_at' => now()->subDays(15),
            'video_url' => 'https://www.youtube.com/watch?v=vV_Xl6H-lUo',
            'views' => 8920
        ]);

        Documentary::create([
            'user_id' => $adminId,
            'title' => 'The Digital Silk Road',
            'description' => 'How global connectivity and high-speed fiber optics are bridging the gap between continents.',
            'content' => '<p>Undersea cables carry 99% of the world\'s data. This documentary explores the geopolitics of the internet and how digital infrastructure is the new gold of the 21st century.</p>',
            'status' => 'published',
            'published_at' => now()->subWeeks(3),
            'video_url' => 'https://www.youtube.com/watch?v=S3Xp6pEq_uE',
            'views' => 4500
        ]);

        Documentary::create([
            'user_id' => $adminId,
            'title' => 'The Renaissance of Space Travel',
            'description' => 'Commercial spaceflight is booming. Meet the pioneers who are making space accessible for everyone.',
            'content' => '<p>From SpaceX to Blue Origin, the commercial space race is accelerating. We look at the technology making reusable rockets a reality and the dream of becoming a multi-planetary species.</p>',
            'status' => 'published',
            'published_at' => now()->subMonths(2),
            'video_url' => 'https://www.youtube.com/watch?v=vlVkPue-YlM',
            'views' => 12000
        ]);
    }
}
