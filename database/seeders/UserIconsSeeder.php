<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserIcon;

class UserIconsSeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            ['icon_path' => '/images/icons/lion.webp'],
            ['icon_path' => '/images/icons/gorilla.webp'],
            ['icon_path' => '/images/icons/hippopotamus.webp'],
            ['icon_path' => '/images/icons/giraffe.webp'],
            ['icon_path' => '/images/icons/rabbit.webp'],
            ['icon_path' => '/images/icons/dog.webp'],
            ['icon_path' => '/images/icons/cat.webp'],
        ];

        foreach ($icons as $icon) {
            UserIcon::create($icon);
        }
    }
}