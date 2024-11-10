<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 25人の男性ユーザーを作成
        User::factory()->count(25)->create([
            'gender' => 'male',
        ]);

        // 25人の女性ユーザーを作成
        User::factory()->count(25)->create([
            'gender' => 'female',
        ]);
    }
}
