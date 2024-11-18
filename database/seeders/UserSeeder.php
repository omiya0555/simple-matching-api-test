<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 男性 25 人を作成
        User::factory()->count(25)->male()->create();

        // 女性 25 人を作成
        User::factory()->count(25)->female()->create();

        // 検証用アカウント
        DB::table('users')->insert([
            [
                'name'          => 'testuser', // 検証用アカウント
                'email'         => 'test@gmail.com',
                'password'      => Hash::make('testuser'),
                'icon_id'       => 1, // アイコンID（例: 1番目の男性アイコン）
                'birth_date'    => '2003-11-12',
                'gender'        => 'male',
                'location'      => '山口県',
                'is_verified'   => 1,
            ],
        ]);
    }
}
