<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * チャットルームのメッセージ一覧を取得
     */
    public function index($chatRoomId)
    {
        // 指定されたチャットルーム内のメッセージを取得
        $messages = Message::where('chat_room_id', $chatRoomId)
            ->with(['user' => function ($query) {
                $query->select('users.id', 'users.name', 'user_icons.icon_path as profile_image')
                      ->leftJoin('user_icons', 'users.icon_id', '=', 'user_icons.id');
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * 新しいメッセージを送信
     */
    public function store(Request $request, $chatRoomId)
    {
        $userId  = $request->user()->id;
        $content = $request->input('content');

        // メッセージを作成
        $message = Message::create([
            'chat_room_id'  => $chatRoomId,
            'user_id'       => $userId,
            'content'       => $content,
        ]);

        // イベントを発火
        broadcast(new MessageSent($message))->toOthers();
        
        return response()->json([
            'message' => 'メッセージが送信されました',
            'data' => $message
        ], 201);
    }
}
