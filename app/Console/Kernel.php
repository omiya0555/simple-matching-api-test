<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Redis; // オンラインユーザーを定期的に確認するため使用

class Kernel extends ConsoleKernel
{
    /**
     * Artisan コマンドのためのアプリケーションの起動処理.
     */
    protected function bootstrappers()
    {
        parent::bootstrappers();
    }

    /**
     * アプリケーションのコンソールコマンドの定義.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * スケジュールの定義.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
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
    }
}
