<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['room_name', 'is_group'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // チャットルームの最後のメッセージ
    // 一覧に表示するために専用で定義
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}