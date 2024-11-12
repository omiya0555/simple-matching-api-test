<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        // 日本人男性の名前リスト
        $maleNames   = ['Yuta', 'Satoshi', 'Tanaka', 'Kenji', 'Taro', 'Hiroshi', 'Takashi', 'Kenta', 'Shota', 'Ryota'];
        
        // 日本人女性の名前リスト
        $femaleNames = ['Sana', 'Yui', 'Hana', 'Miki', 'Rina', 'Haruka', 'Ayumi', 'Naoko', 'Yoko', 'Eriko'];

        // 日本の都道府県リスト
        $japaneseLocations = [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県',
            '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
            '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県',
            '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        ];

        // 性別をランダムに選択し、対応する名前リストから名前をランダムに選択
        $gender = $this->faker->randomElement(['male', 'female']);
        $name   = $gender === 'male'
            ? $this->faker->randomElement($maleNames)
            : $this->faker->randomElement($femaleNames);

        return [
            'name'              => $name,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), 
            'profile_image'     => $this->faker->imageUrl(200, 200, 'people'),
            'birth_date'        => $this->faker->dateTimeBetween('-40 years', '-18 years'),
            'gender'            => $gender,
            'location'          => $this->faker->randomElement($japaneseLocations),
            'is_verified'       => $this->faker->boolean(70), // 70%の確率で本人確認済み
            'remember_token'    => Str::random(10),
        ];
    }
}