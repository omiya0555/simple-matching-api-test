<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Models\ChatRoom;

class ChatRoomController extends Controller
{
    /**
     * ユーザーが属するチャットルームの一覧を取得
     * マッチした人とのチャットルーム一覧
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $onlineUsers = Redis::hkeys('online_users'); // オンラインユーザー情報を取得

        // ユーザーが参加しているチャットルームを取得
        // 併せてユーザー情報、最新のメッセージも取得
        $chatRooms = ChatRoom::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with([
            'users:id,name,profile_image',
            'latestMessage' => function ($query) {
                $query->select(
                    'messages.user_id', 
                    'messages.chat_room_id', 
                    'content', 
                    'created_at'
                );
            }
        ])->get();

        // 各チャットルームのユーザー情報を加工
        $chatRooms->each(function ($chatRoom) use ($onlineUsers, $userId) {
            // 本人を除外し、オンラインフラグを追加
            $chatRoom->users = $chatRoom->users->filter(function ($user) use ($userId, $onlineUsers) {
                if ($user->id !== $userId) {
                    $user->is_online = in_array($user->id, $onlineUsers);
                    return true;
                }
                return false;
            });
        });

        // pivotによる冗長な情報 や 自身の情報等 を フィルターする必要がある。
        return response()->json($chatRooms);
    }
}
