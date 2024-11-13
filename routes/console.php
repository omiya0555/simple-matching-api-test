<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Redis;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $onlineUsers = Redis::hgetall('online_users');
    $currentTime = now()->timestamp;

    // 現在時刻 - 最終アクティブ時刻 = 10分以上 なら削除
    // 上の確認をオンラインユーザー数　繰り返す
    foreach ($onlineUsers as $userId => $lastActive) {
        if ($currentTime - (int)$lastActive > 600) {  // 10分以上経過　サービスへの定義検討
            Redis::hdel('online_users', $userId);
        }
    }
})->everyMinute();