<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\Like;
use App\Models\User;

class LikeController extends Controller
{
    /**
     * いいねしたユーザーの取得
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // いいねしたユーザーのIDリストを取得
        $likedUserIds = Like::where('sender_id', $userId)->pluck('receiver_id');

        // いいねしたユーザーの詳細情報を Users テーブルから取得し、アイコンパスを結合
        $likedUsers = User::select('users.id', 'users.name', 'user_icons.icon_path as profile_image')
            ->leftJoin('user_icons', 'users.icon_id', '=', 'user_icons.id')
            ->whereIn('users.id', $likedUserIds)
            ->get();

        return response()->json($likedUsers, 200);
    }

    /**
     * いいねを送信するメソッド
     */
    public function sendLike(Request $request)
    {
        $senderId   = $request->user()->id;
        $receiverId = $request->input('receiver_id');

        // すでにいいねがあるかを確認
        $existingLike = Like::where('sender_id', $senderId)
                        ->where('receiver_id', $receiverId)
                        ->first();

        if ($existingLike) {
            return response()->json(['message' => '既にいいね済みです'], 200);
        }

        // いいねを保存
        Like::create([
            'sender_id'     => $senderId,
            'receiver_id'   => $receiverId,
        ]);

        // 相互のいいねがあるか確認してマッチングを判定
        // 送信者が相手で、受信者が自分のLIKEがテーブルにあるか
        if (Like::where('sender_id', $receiverId)->where('receiver_id', $senderId)->exists()) {
            // マッチング成立、チャットルームを作成  !!!It's match!!!
            $chatRoom = ChatRoom::create([]);
            $chatRoom->users()->attach([$senderId, $receiverId]);

            return response()->json([
                'message'       => 'マッチング成立',
                'chat_room_id'  => $chatRoom->id
            ], 201);
        }

        return response()->json(['message' => 'いいねを送りました'], 201);
    }

}
