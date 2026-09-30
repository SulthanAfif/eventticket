<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Teknologi & IT',
                'slug' => 'teknologi-it',
                'icon' => '💻',
                'color' => 'indigo',
                'description' => 'Konferensi, meetup developer, workshop coding, dan event teknologi.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Musik & Konser',
                'slug' => 'musik-konser',
                'icon' => '🎵',
                'color' => 'rose',
                'description' => 'Konser, festival musik, jazz night, dan pertunjukan live.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Bisnis & Startup',
                'slug' => 'bisnis-startup',
                'icon' => '🚀',
                'color' => 'amber',
                'description' => 'Pitch day, networking founder, seminar bisnis, dan workshop entrepreneurship.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Desain & Kreatif',
                'slug' => 'desain-kreatif',
                'icon' => '🎨',
                'color' => 'violet',
                'description' => 'Workshop UI/UX, desain grafis, fotografi, dan creative talk.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Olahraga & Kesehatan',
                'slug' => 'olahraga-kesehatan',
                'icon' => '🏃',
                'color' => 'emerald',
                'description' => 'Lari, yoga, fitness class, dan seminar kesehatan.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Edukasi & Workshop',
                'slug' => 'edukasi-workshop',
                'icon' => '📚',
                'color' => 'sky',
                'description' => 'Kelas skill, seminar pendidikan, dan pelatihan profesional.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Kuliner & Food',
                'slug' => 'kuliner-food',
                'icon' => '🍜',
                'color' => 'orange',
                'description' => 'Food festival, cooking class, dan tasting event.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Seni & Budaya',
                'slug' => 'seni-budaya',
                'icon' => '🎭',
                'color' => 'pink',
                'description' => 'Pameran seni, teater, tari, dan festival budaya.',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
