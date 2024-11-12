<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 25人の男性ユーザーを作成
        User::factory()->count(50)->create();

        DB::table('users')->insert([
            [
                'name'          => 'testuser', // 検証用アカウント
                'email'         => 'test@gmail.com',
                'password'      => Hash::make('testuser'),
                'profile_image' => 'images/icons/testuser.png',
                'birth_date'    => '2003-11-12',
                'gender'        => 'male',
                'location'      => '山口県',
                'is_verified'   => 1,
            ],
        ]);
    }
}
