<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $img = fn ($id) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=900&q=80";

        $items = [
            ['Beaches', '1507525428034-b723cf961d3e'],
            ['Beaches', '1505228395891-9a51e7e86bf6'],
            ['Mountains', '1464822759023-fed622ff2c3b'],
            ['Mountains', '1454496522488-7a8e488e8606'],
            ['Cities', '1512453979798-5ea266f8880c'],
            ['Cities', '1493976040374-85c8e12f0c0e'],
            ['Culture', '1528360983277-13d401cdc186'],
            ['Culture', '1545569341-9eb8b30979d9'],
            ['Beaches', '1514282401047-d79a71a590e8'],
            ['Mountains', '1531366936337-7c912a4589a7'],
            ['Cities', '1570077188670-e3a8d69ac5ff'],
            ['Culture', '1537996194471-e657df975ab4'],
        ];

        foreach ($items as $i => [$category, $id]) {
            Gallery::updateOrCreate(
                ['image' => $img($id)],
                ['title' => $category . ' View', 'category' => $category, 'is_active' => true, 'sort_order' => $i]
            );
        }
    }
}
