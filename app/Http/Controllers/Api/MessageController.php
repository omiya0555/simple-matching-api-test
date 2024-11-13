<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            ->with('user:id,name,profile_image') // 送信者の情報を取得
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

        return response()->json([
            'message' => 'メッセージが送信されました',
            'data' => $message
        ], 201);
    }
}
