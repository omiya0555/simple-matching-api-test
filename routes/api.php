<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ChatRoomController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;

Route::post('/login', [AuthController::class, 'login']); // ログイン

Route::middleware('auth:sanctum')->group(function () {
    // AuthController: 認証関連のルート
    Route::post('/logout',      [AuthController::class, 'logout']);         // ログアウト処理
    Route::get( '/user',        [AuthController::class, 'user']);           // ログインユーザー情報取得

    // UserController: ユーザー関連のルート
    Route::get('/users', [UserController::class, 'index']);                 // ユーザー一覧
    Route::get('/users/{id}', [UserController::class, 'show']);             // ユーザー詳細

    // LikeController: いいね関連のルート
    Route::post('/likes', [LikeController::class, 'store']);                // いいね送信
    Route::get('/likes/matches', [LikeController::class, 'getMatches']);    // マッチングの確認
    Route::get('/chat-rooms', [ChatRoomController::class, 'index']);        // チャットルーム一覧
    Route::post('/chat-rooms', [ChatRoomController::class, 'store']);       // チャットルーム作成
    Route::get('/chat-rooms/{id}', [ChatRoomController::class, 'show']);    // 特定チャットルーム取得

    // ChatRoomController: チャットルーム関連のルート
    Route::post('/chat-rooms/{chatRoom}/messages', [MessageController::class, 'store']);    // メッセージ送信
    Route::get('/chat-rooms/{chatRoom}/messages', [MessageController::class, 'index']);     // メッセージ取得

    // NotificationController: 通知関連のルート
    Route::get('/notifications', [NotificationController::class, 'index']);                 // 通知一覧
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);  // 通知既読処理
});