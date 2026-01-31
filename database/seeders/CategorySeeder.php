<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'anal', 'arab', 'bathroom', 'big tits', 'big ass', 'bikini', 'blonde', 'cheating', 'cop', 'outdoors',
            'creampie', 'cum swap', 'doctor', 'foursome', "friend's mom", 'gangbang', 'gym', 'hairy', 'hardcore',
            'japanese', 'kitchen', 'latex', 'maid', 'massage', 'milf', 'mother in law', "mom's friend", 'movie',
            'natural tits', 'nurse', 'office', 'oil', 'red head', 'small tits', 'school', 'squirt', 'stepmom',
            'stepsister', 'stockings', 'student', 'swallow', 'tattoo', 'teacher', 'teen', 'threesome', 'uniform',
            'wedding', 'workout', 'x mas', 'cum inside', 'underwear', 'medium tits', 'boss', 'asian', 'neighbor',
            'wearing', 'nice tits', 'beautiful pussy', 'pool', 'man with many women',
        ];

        $now = now();

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'title' => $category,
                'slug' => str($category)->slug()->toString(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Category::insert($data);
    }
}
