<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $bySlug = Category::pluck('id', 'slug');

        $events = [
            // —— Teknologi & IT ——
            [
                'category' => 'teknologi-it',
                'title' => 'Tech Conference Indonesia 2026',
                'slug' => 'tech-conference-indonesia-2026',
                'description' => "Konferensi teknologi tahunan dengan pembicara industri, workshop hands-on, dan networking session.\n\nCocok untuk developer, product manager, dan founder.",
                'venue' => 'Jakarta Convention Center',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(14)->setTime(9, 0),
                'ends_at' => now()->addDays(14)->setTime(17, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80',
                'ticket_bg_color' => 'indigo',
                'tickets' => [
                    ['name' => 'Regular', 'price' => 150000, 'quota' => 200],
                    ['name' => 'VIP', 'price' => 350000, 'quota' => 50],
                ],
            ],
            [
                'category' => 'teknologi-it',
                'title' => 'DevOps Meetup Jakarta',
                'slug' => 'devops-meetup-jakarta',
                'description' => "Sharing session tentang CI/CD, Kubernetes, dan observability. Bawa laptop untuk live demo.",
                'venue' => 'GoWork Pacific Place',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(7)->setTime(18, 30),
                'ends_at' => now()->addDays(7)->setTime(21, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&q=80',
                'ticket_bg_color' => 'dark',
                'tickets' => [
                    ['name' => 'Gratis', 'price' => 0, 'quota' => 80],
                ],
            ],
            [
                'category' => 'teknologi-it',
                'title' => 'AI & Machine Learning Summit',
                'slug' => 'ai-ml-summit-2026',
                'description' => "Summit seharian tentang AI generatif, LLM, dan penerapan ML di industri Indonesia.",
                'venue' => 'Balai Kartini',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(45)->setTime(8, 30),
                'ends_at' => now()->addDays(45)->setTime(17, 30),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&q=80',
                'ticket_bg_color' => 'purple',
                'tickets' => [
                    ['name' => 'Early Bird', 'price' => 200000, 'quota' => 100],
                    ['name' => 'Regular', 'price' => 300000, 'quota' => 150],
                    ['name' => 'VIP', 'price' => 500000, 'quota' => 30],
                ],
            ],

            // —— Musik & Konser ——
            [
                'category' => 'musik-konser',
                'title' => 'Jazz Night Under the Stars',
                'slug' => 'jazz-night-under-the-stars',
                'description' => "Malam jazz outdoor dengan musisi lokal dan internasional. Food truck dan seating area tersedia.",
                'venue' => 'Taman Menteng',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(30)->setTime(18, 30),
                'ends_at' => now()->addDays(30)->setTime(22, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1514320291840-309db1933a2c?w=800&q=80',
                'ticket_bg_color' => 'sunset',
                'tickets' => [
                    ['name' => 'Standing', 'price' => 125000, 'quota' => 300],
                    ['name' => 'Seated', 'price' => 200000, 'quota' => 100],
                ],
            ],
            [
                'category' => 'musik-konser',
                'title' => 'Indie Music Festival Bandung',
                'slug' => 'indie-music-festival-bandung',
                'description' => "Festival musik indie 2 hari dengan 20+ band lokal. Area camping, merchandise, dan food court.",
                'venue' => 'Gasibu Field',
                'city' => 'Bandung',
                'starts_at' => now()->addDays(60)->setTime(14, 0),
                'ends_at' => now()->addDays(61)->setTime(23, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1459749411175-04bf5296774d?w=800&q=80',
                'ticket_bg_color' => 'purple',
                'tickets' => [
                    ['name' => '1 Day Pass', 'price' => 175000, 'quota' => 500],
                    ['name' => '2 Day Pass', 'price' => 300000, 'quota' => 300],
                ],
            ],

            // —— Bisnis & Startup ——
            [
                'category' => 'bisnis-startup',
                'title' => 'Startup Pitch Day',
                'slug' => 'startup-pitch-day',
                'description' => "Saksikan startup early-stage mempresentasikan ide mereka di depan investor dan mentor.",
                'venue' => 'Impact Hub Jakarta',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(10)->setTime(13, 0),
                'ends_at' => now()->addDays(10)->setTime(18, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&q=80',
                'ticket_bg_color' => 'gold',
                'tickets' => [
                    ['name' => 'Audience', 'price' => 25000, 'quota' => 150],
                ],
            ],
            [
                'category' => 'bisnis-startup',
                'title' => 'Founder Networking Night',
                'slug' => 'founder-networking-night',
                'description' => "Malam networking eksklusif untuk founder, investor, dan operator startup. Limited seats.",
                'venue' => 'The Ritz-Carlton Pacific Place',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(20)->setTime(19, 0),
                'ends_at' => now()->addDays(20)->setTime(22, 30),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&q=80',
                'ticket_bg_color' => 'dark',
                'tickets' => [
                    ['name' => 'Member', 'price' => 150000, 'quota' => 80],
                    ['name' => 'Guest', 'price' => 250000, 'quota' => 40],
                ],
            ],

            // —— Desain & Kreatif ——
            [
                'category' => 'desain-kreatif',
                'title' => 'Workshop UI/UX Design',
                'slug' => 'workshop-uiux-design',
                'description' => "Workshop intensif desain antarmuka modern dengan Figma. Dari wireframe hingga prototype interaktif.",
                'venue' => 'Co-Working Space Bandung',
                'city' => 'Bandung',
                'starts_at' => now()->addDays(21)->setTime(9, 0),
                'ends_at' => now()->addDays(21)->setTime(16, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&q=80',
                'ticket_bg_color' => 'purple',
                'tickets' => [
                    ['name' => 'Early Bird', 'price' => 75000, 'quota' => 40],
                    ['name' => 'Regular', 'price' => 100000, 'quota' => 60],
                ],
            ],
            [
                'category' => 'desain-kreatif',
                'title' => 'Photography Masterclass',
                'slug' => 'photography-masterclass',
                'description' => "Kelas fotografi portrait & street photography bersama fotografer profesional. Bawa kamera sendiri.",
                'venue' => 'Taman Sari',
                'city' => 'Yogyakarta',
                'starts_at' => now()->addDays(28)->setTime(8, 0),
                'ends_at' => now()->addDays(28)->setTime(15, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1452587925148-ce544e77e70d?w=800&q=80',
                'ticket_bg_color' => 'emerald',
                'tickets' => [
                    ['name' => 'Peserta', 'price' => 185000, 'quota' => 25],
                ],
            ],

            // —— Olahraga & Kesehatan ——
            [
                'category' => 'olahraga-kesehatan',
                'title' => 'Jakarta Morning Run 10K',
                'slug' => 'jakarta-morning-run-10k',
                'description' => "Lari 10K di jalur car free day. Termasuk race pack, medali finisher, dan refreshment.",
                'venue' => 'Bundaran HI',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(35)->setTime(6, 0),
                'ends_at' => now()->addDays(35)->setTime(10, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1452626038306-9cf8c0c4e9e1?w=800&q=80',
                'ticket_bg_color' => 'emerald',
                'tickets' => [
                    ['name' => 'Early Bird', 'price' => 150000, 'quota' => 200],
                    ['name' => 'Regular', 'price' => 200000, 'quota' => 500],
                ],
            ],
            [
                'category' => 'olahraga-kesehatan',
                'title' => 'Yoga & Mindfulness Retreat',
                'slug' => 'yoga-mindfulness-retreat',
                'description' => "Half-day retreat yoga, meditasi, dan healthy brunch. Cocok untuk pemula maupun advanced.",
                'venue' => 'Ubud Garden',
                'city' => 'Bali',
                'starts_at' => now()->addDays(40)->setTime(7, 0),
                'ends_at' => now()->addDays(40)->setTime(13, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&q=80',
                'ticket_bg_color' => 'emerald',
                'tickets' => [
                    ['name' => 'Standard', 'price' => 275000, 'quota' => 40],
                ],
            ],

            // —— Edukasi & Workshop ——
            [
                'category' => 'edukasi-workshop',
                'title' => 'Public Speaking Intensive',
                'slug' => 'public-speaking-intensive',
                'description' => "Workshop 1 hari menguasai presentasi, storytelling, dan confidence di depan audiens.",
                'venue' => 'Ruang Belajar Menteng',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(12)->setTime(9, 0),
                'ends_at' => now()->addDays(12)->setTime(17, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&q=80',
                'ticket_bg_color' => 'indigo',
                'tickets' => [
                    ['name' => 'Peserta', 'price' => 225000, 'quota' => 30],
                ],
            ],
            [
                'category' => 'edukasi-workshop',
                'title' => 'Digital Marketing Bootcamp',
                'slug' => 'digital-marketing-bootcamp',
                'description' => "Bootcamp 2 hari: SEO, Meta Ads, content strategy, dan analytics untuk UMKM & startup.",
                'venue' => 'WeWork Sinarmas',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(25)->setTime(9, 0),
                'ends_at' => now()->addDays(26)->setTime(17, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
                'ticket_bg_color' => 'gold',
                'tickets' => [
                    ['name' => 'Early Bird', 'price' => 450000, 'quota' => 40],
                    ['name' => 'Regular', 'price' => 599000, 'quota' => 60],
                ],
            ],

            // —— Kuliner & Food ——
            [
                'category' => 'kuliner-food',
                'title' => 'Jakarta Street Food Festival',
                'slug' => 'jakarta-street-food-festival',
                'description' => "Festival kuliner jalanan dari seluruh Indonesia. 50+ tenant, live music, dan cooking demo.",
                'venue' => 'Lapangan Banteng',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(18)->setTime(11, 0),
                'ends_at' => now()->addDays(19)->setTime(22, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1555930414-3783c0c5a0b6?w=800&q=80',
                'ticket_bg_color' => 'sunset',
                'tickets' => [
                    ['name' => 'Entrance', 'price' => 25000, 'quota' => 2000],
                    ['name' => 'VIP Lounge', 'price' => 150000, 'quota' => 100],
                ],
            ],
            [
                'category' => 'kuliner-food',
                'title' => 'Baking Class: Sourdough & Pastry',
                'slug' => 'baking-class-sourdough-pastry',
                'description' => "Belajar membuat sourdough starter, baguette, dan croissant dari chef profesional.",
                'venue' => 'Kitchen Studio Kemang',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(16)->setTime(10, 0),
                'ends_at' => now()->addDays(16)->setTime(15, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&q=80',
                'ticket_bg_color' => 'gold',
                'tickets' => [
                    ['name' => 'Peserta', 'price' => 350000, 'quota' => 16],
                ],
            ],

            // —— Seni & Budaya ——
            [
                'category' => 'seni-budaya',
                'title' => 'Contemporary Art Exhibition',
                'slug' => 'contemporary-art-exhibition',
                'description' => "Pameran seni kontemporer karya 15 seniman Indonesia. Opening night dengan artist talk.",
                'venue' => 'Museum MACAN',
                'city' => 'Jakarta',
                'starts_at' => now()->addDays(8)->setTime(10, 0),
                'ends_at' => now()->addDays(22)->setTime(18, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1536924940846-227afb31e2a5?w=800&q=80',
                'ticket_bg_color' => 'dark',
                'tickets' => [
                    ['name' => 'Weekday', 'price' => 50000, 'quota' => 500],
                    ['name' => 'Weekend', 'price' => 75000, 'quota' => 500],
                    ['name' => 'Opening Night', 'price' => 150000, 'quota' => 100],
                ],
            ],
            [
                'category' => 'seni-budaya',
                'title' => 'Wayang & Gamelan Night',
                'slug' => 'wayang-gamelan-night',
                'description' => "Pertunjukan wayang kulit klasik dilengkapi gamelan live. Pengenalan budaya Jawa untuk semua usia.",
                'venue' => 'Pendopo Agung',
                'city' => 'Yogyakarta',
                'starts_at' => now()->addDays(22)->setTime(19, 0),
                'ends_at' => now()->addDays(22)->setTime(22, 0),
                'status' => 'published',
                'poster' => 'https://images.unsplash.com/photo-1565034946487-077786925e79?w=800&q=80',
                'ticket_bg_color' => 'gold',
                'tickets' => [
                    ['name' => 'Regular', 'price' => 75000, 'quota' => 200],
                    ['name' => 'VIP Front Row', 'price' => 150000, 'quota' => 40],
                ],
            ],
        ];

        foreach ($events as $data) {
            $tickets = $data['tickets'];
            $categorySlug = $data['category'];
            unset($data['tickets'], $data['category']);

            $data['category_id'] = $bySlug[$categorySlug] ?? null;

            $event = Event::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Hapus ticket type lama lalu buat ulang (idempotent untuk re-seed)
            $event->ticketTypes()->delete();

            foreach ($tickets as $t) {
                TicketType::create([
                    'event_id' => $event->id,
                    'name' => $t['name'],
                    'price' => $t['price'],
                    'quota' => $t['quota'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
