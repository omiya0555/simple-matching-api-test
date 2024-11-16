<?php

use Illuminate\Support\Facades\Broadcast;

// 認可ロジック
Broadcast::channel('chat-room.{chatRoomId}', function ($user, $chatRoomId) {
    return $user->chatRooms()->where('chat_room_id', $chatRoomId)->exists();
});
